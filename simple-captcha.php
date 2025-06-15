<?php

namespace Grav\Plugin;

use Composer\Autoload\ClassLoader;
use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;
use SimpleCaptcha\Builder;

/**
 * Class SimpleCaptchaPlugin
 * @package Grav\Plugin
 */
class SimpleCaptchaPlugin extends Plugin
{
    protected Builder $simpleCaptcha;

    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onPluginsInitialized' => [
                // Uncomment following line when plugin requires Grav < 1.7
                // ['autoload', 100000],
                ['onPluginsInitialized', 0],
            ],
        ];
    }

    /**
     * Composer autoload
     *
     * @return ClassLoader
     */
    public function autoload(): ClassLoader
    {
        return require __DIR__ . '/vendor/autoload.php';
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized(): void
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        $events = [
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
            'onTwigSiteVariables' => ['onTwigSiteVariables', 0],
            'onFormProcessed' => ['onFormProcessed', 0],
        ];

        // Enable the main events we are interested in
        $this->enable($events);
    }

    /**
     * Add current directory to twig lookup paths.
     */
    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    /**
     * Make form accessible from twig.
     */
    public function onTwigSiteVariables()
    {
         // Don't proceed if we are in the admin plugin
         if ($this->isAdmin()) {
            return;
        }

        $this->simpleCaptcha = $this->initCaptcha();

        $this->grav['twig']->twig_vars['simplecaptcha'] = $this->simpleCaptcha;
    }

    /**
     * Process the sweetcaptcha logic
     *
     * @param Event $event
     */
    public function onFormProcessed(Event $event)
    {
        $form = $event['form'];
        $action = $event['action'];

        switch ($action) {
            case 'simplecaptcha':
                // make sure we have the details
                $phrase = $form->getValue('simplecaptchaphrase');
                if (!$phrase || $phrase !== $this->getSessionPhrase()) {
                    $this->grav->fireEvent('onFormValidationError', new Event([
                        'form'    => $form,
                        'message' => $this->grav['language']->translate('PLUGIN_FORM.ERROR_VALIDATING_CAPTCHA')
                    ]));
                    $event->stopPropagation();
                }

                // Generate a new captcha
                $this->initCaptcha();

                break;
        }
    }

    // override defaults
    public function getFormFieldTypes()
    {
        return [
            'simplecaptcha' => [
                'input@' => false
            ]
        ];
    }

    private function initCaptcha(): Builder
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }

        $builder = Builder::create();
        $builder->bgColor = [255, 255, 255];
        $builder->textColor = [0, 0, 0];
        $builder->distort = $this->config->get('plugins.simple-captcha.distort', false);
        $builder->applyEffects = false;
        $builder->applyNoise = false;

        // Don't overwrite the captcha if it's already been created
        $this->simpleCaptcha = $builder->build(150, 100);

        $this->setSessionPhrase($builder->phrase);

        return $builder;
    }

    private function getSessionPhrase(): ?string
    {
        $phrase = $this->config->get('plugins.simple-captcha.session_id', 'SIMPLE_CAPTCHA_PHRASE');
        return isset($_SESSION[$phrase]) ? $_SESSION[$phrase] : null;
    }

    private function setSessionPhrase(string $phrase): void
    {
        $_SESSION[$this->config->get('plugins.simple-captcha.session_id', 'SIMPLE_CAPTCHA_PHRASE')] = $phrase;
    }
}
