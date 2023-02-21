<?php

namespace Grav\Plugin;

use Composer\Autoload\ClassLoader;
use Grav\Common\Config\Config;
use Grav\Common\Plugin;
use Grav\Common\Grav;
use SimpleCaptcha\Builder;
use RocketTheme\Toolbox\Event\Event;

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
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
            'onTwigSiteVariables' => ['onTwigSiteVariables', 0],
            'onFormProcessed' => ['onFormProcessed', 0],
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
        $this->initBuilder();

        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        // Enable the main events we are interested in
        $this->enable([
            // Put your main events here
        ]);
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
                if ($phrase && $this->simpleCaptcha->compare($phrase)) {
                    // do nothing captcha passed successfully
                } else {
                    $this->grav->fireEvent('onFormValidationError', new Event([
                        'form'    => $form,
                        'message' => $this->grav['language']->translate('PLUGIN_FORM.ERROR_VALIDATING_CAPTCHA')
                    ]));
                    $event->stopPropagation();
                }

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

    private function initBuilder()
    {
        $builder = Builder::create();
        $builder->bgColor = [255, 255, 255];
        $builder->textColor = [0, 0, 0];
        $builder->distort = false;
        $builder->applyEffects = false;
        $builder->applyNoise = false;

        $this->simpleCaptcha = $builder->build(150, 100);
    }
}
