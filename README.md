# Simple Captcha Plugin
[![Build Status](https://github.com/gschafra/grav-plugin-simple-captcha/actions/workflows/quality-workflow.yml/badge.svg)](https://github.com/gschafra/grav-plugin-simple-captcha/actions?workflow=Quality+Build)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=gschafra_grav-plugin-simple-captcha&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=gschafra_grav-plugin-simple-captcha)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/2c839cbd7e1843d1bec3747057c1a228)](https://app.codacy.com/gh/gschafra/grav-plugin-simple-captcha/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)
[![Latest](https://img.shields.io/github/release/gschafra/grav-plugin-simple-captcha.svg)](https://github.com/gschafra/grav-plugin-simple-captcha)
![Snyk](https://snyk.io/test/github/gschafra/grav-plugin-simple-captcha/badge.svg)

**This README.md file should be modified to describe the features, installation, configuration, and general usage of the plugin.**

The **Simple Captcha** Plugin is an extension for [Grav CMS](https://github.com/getgrav/grav). It provides captchas for Grav forms using SimpleCaptcha.

## Installation

Installing the Simple Captcha plugin can be done in one of three ways: The GPM (Grav Package Manager) installation method lets you quickly install the plugin with a simple terminal command, the manual method lets you do so via a zip file, and the admin method lets you do so via the Admin Plugin.

### GPM Installation (Preferred)

To install the plugin via the [GPM](https://learn.getgrav.org/cli-console/grav-cli-gpm), through your system's terminal (also called the command line), navigate to the root of your Grav-installation, and enter:

    bin/gpm install simple-captcha

This will install the Simple Captcha plugin into your `/user/plugins`-directory within Grav. Its files can be found under `/your/site/grav/user/plugins/simple-captcha`.

### Manual Installation

To install the plugin manually, download the zip-version of this repository and unzip it under `/your/site/grav/user/plugins`. Then rename the folder to `simple-captcha`. You can find these files on [GitHub](https://github.com/gschafra/grav-plugin-simple-captcha) or via [GetGrav.org](https://getgrav.org/downloads/plugins).

You should now have all the plugin files under

    /your/site/grav/user/plugins/simple-captcha

> NOTE: This plugin is a modular component for Grav which may require other plugins to operate, please see its [blueprints.yaml-file on GitHub](https://github.com/gschafra/grav-plugin-simple-captcha/blob/main/blueprints.yaml).

### Admin Plugin

If you use the Admin Plugin, you can install the plugin directly by browsing the `Plugins`-menu and clicking on the `Add` button.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/simple-captcha/simple-captcha.yaml` to `user/config/plugins/simple-captcha.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:

```yaml
enabled: true
```

Note that if you use the Admin Plugin, a file with your configuration named simple-captcha.yaml will be saved in the `user/config/plugins/`-folder once the configuration is saved in the Admin.

## Usage

**Describe how to use the plugin.**

## Credits

**Did you incorporate third-party code? Want to thank somebody?**

## To Do

- [ ] Future plans, if any

