# Hubspot Forms
Select your HubSpot forms directly from a CMS field and render them with Twig. No more copy & pasting embed codes! 

## Installation
### From the browser
1. Download the plugin from the Craft Plugin Store
2. Install the plugin from the settings page
3. Enable the plugin from the settings page

### From the terminal
```
composer require jordanbeattie/craftcms-hubspot-forms
```
```
php craft plugin/install hubspot-forms
```
```
php craft plugin/enable hubspot-forms
```

## Configuration
Create a HubSpot private app with the `forms` scope. Copy your access token and add it to the plugin settings `HubSpot Token` field. 
> The HubSpot token field can accept `.env` variables. It is highly recommended that you keep your access token in your `.env` file. 

## CMS Field
The plugin adds a "HubSpot Form" field type where you can allow users to select a form present in your HubSpot account. See the templating section on how to render forms from the field.

## Templating
Use the plugins `render()` function to output the form to the template. This requires you to pass the HubSpot form field (or form ID). 
```
{{ craft.hubspotforms.render( myHubspotFormField ) }}
```
Example:
```
{{ craft.hubspotforms.render( entry.form ) }}
```

### Optional Attributes
You can pass a JavaScript event via the `loadOnEvent` attribute to trigger the form to load when the JavaScript event is fired. 
```
{{ craft.hubspotforms.render( myHubspotFormField, {
    loadOnEvent: 'myJavaScriptEvent'
}) }}
```
Example:
```
{{ craft.hubspotforms.render( entry.form, {
    loadOnEvent: 'DOMContentLoaded'
}) }}
```

## Migrating from HubCraft
If you were previously using the jordanbeattie/craftcms-hubspot plugin, you can update your existing fields to the form field provided by this plugin with a simple command. 

```
php craft hubspot-forms/migrate
```

This will ensure your plugin is installed and configured correctly and then list out each of the old fields before asking you to continue. 

By continuing, each of the old fields will be updated to the HubspotFormDropdown provided with this plugin. 

Once this is complete, you should update your templates to use the new syntax. 

**Old syntax:** 
```
{{ craft.hubspot.render( myFieldHandle ) }}
```

**New syntax:**
```
{{ craft.hubspotforms.render( myFieldHandle ) }}
```

Once your templates are updated, you can safely uninstall the HubCraft plugin. 

## Support
[jordanbeattie.com](https://jordanbeattie.com)