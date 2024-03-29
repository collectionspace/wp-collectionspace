# wp-collectionspace

A WordPress plugin for CollectionSpace.

This plugin embeds a CollectionSpace collection browser into your WordPress site. It automates some of the steps needed to install the public browser, specifically for WordPress. For details about those steps, or to install the public browser onto a site that uses a different web server, see the [installation instructions](https://github.com/collectionspace/cspace-public-browser.js/blob/master/docs/installing.md) in the [cspace-public-browser.js](https://github.com/collectionspace/cspace-public-browser.js) project.

To use this plugin, you must have a CollectionSpace 6.0 (or above) installation that has been configured to index records into an Elasticsearch cluster, and that has a running CollectionSpace public gateway service.

## Installing the Plugin

To install the plugin, download the plugin zip file, and upload it to your WordPress server.

1. On the [Releases](https://github.com/collectionspace/wp-collectionspace/releases) page, find the version of the plugin you want to use. Usually, you should use the latest version. In the Assets section of the version you want, click on the `wp-collectionspace.zip` file to download it to your computer.

1. Log in to your WordPress site. In the Dashboard menu, select Plugins, click the Add New button, then click the Upload Plugin button. Select the `wp-collectionspace.zip` file that you just downloaded.

1. When the plugin installation completes, click the Activate Plugin button.

## Configuring the Plugin

A typical plugin configuration requires adding and configuring a CollectionSpace browser, adding a menu option, and adding some CSS rules.

The following instructions describe how to configure the plugin using the WordPress admin area, accessed at /wp-admin.

### Adding a CollectionSpace Browser

Once the plugin has been activated, CollectionSpace Browsers will appear as an option in your WordPress menu. To add a collections browser to your site:

1. Click on CollectionSpace Browsers in the WordPress menu, then click the Add New button.

1. Enter a title for the browser. The title will be displayed by default in permalink URLs and menu options visible to users, so it should be short and descriptive, e.g. "Collection", "Paintings", "Art".

1. Edit the permalink for the browser. A default will be generated for you based on the title. The permalink will be visible to your site's visitors in the browser's URL bar, so it should be short and descriptive, e.g. "collection", "paintings", "art".

1. Set custom fields to configure the browser. There are two custom fields. Both are required:
   - `script location`: The URL to the CollectionSpace browser JavaScript application, e.g. `https://cdn.jsdelivr.net/npm/@collectionspace/cspace-public-browser@1.5.1/dist/cspacePublicBrowser.min.js`. Your CollectionSpace administrator should be able to provide the correct value for your CollectionSpace installation.

   - `config`: Configuration settings for the CollectionSpace browser application, in JavaScript object notation (not restricted to JSON). At a minimum, specify the `basename` and `gatewayUrl` settings. `basename` should be set to "/collection/" followed by the slug of the page (the last element of the permalink). `gatewayUrl` should be set the the URL of your CollectionSpace public gateway server. Your CollectionSpace administrator should be able to provide the correct value. For example, if your page slug is `core`, your config might look like:
   ```
   {
      basename: '/collection/core',
      gatewayUrl: 'https://core.dev.collectionspace.org/gateway/core',
   }
   ```
   Ask your CollectionSpace administrator if any additional settings are required for your installation.

### Adding a Menu Option

You'll usually want to add a link to your CollectionSpace browser in your site's top menu, and possibly other menus, so that users will be able to find it easily. To add a link to the top menu:

1. In the WordPress menu, select Appearance, then Menus.

1. Click the Screen Options button at the top right.

1. In the Screen Options panel, under the "Boxes" heading, check the checkbox next to CollectionSpace Browsers.

1. Edit the menu that is selected to be displayed in the "Top Menu" location.

1. Open the "CollectionSpace Browsers" section on the left. Check the browser you just added, and click the Add to Menu button.

1. Edit the label for the menu item. By default, it is set to the title of the browser.

1. Drag the menu items into your preferred order.

1. Click the Save Menu button.

Repeat this process for any other menus to which you wish to add the CollectionSpace browser.

### Adding CSS

Usually you'll need to add some CSS styling rules to make a seamless integration between your WordPress theme and the CollectionSpace browser application. The exact rules will depend on the theme chosen, and your preference for how the site looks. Writing these rules will require knowledge of HTML, CSS, and your web browser's developer tools.

In some cases, you'll need to override styling rules from the WordPress theme, and in other cases, you'll have to override styling from the CollectionSpace browser. To help target these rules, the following classes will appear on the `body` tag of a CollectionSpace browser page:

- `.collectionspace-template-default`: Indicates that this page is a CollectionSpace browser.

- `.has-cspace-SearchResultPage`: Indicates that this page contains a CollectionSpace browser search result listing.

- `.has-cspace-DetailPage`: Indicates that this page contains a CollectionSpace browser detail page.

To determine the exact selectors necessary to override a style, use your browser's developer tools to examine the structure of the HTML page, and the CSS rules that apply to the element you're interested in styling. Then write a more specific selector, using one of the above classes, to override any existing rules.

Some common kinds of styling rules that need to be written include:

- Setting the width of the CollectionSpace browser to match the width of a typical page in the WordPress theme (and the width of the theme's header and footer).
- Adjusting margins and paddings so that a margin/padding provided by the WordPress theme abutting a margin/padding from the CollectionSpace browser does not result in too large a swath of negative space.
- Changing the width and position of the footer when the search result page is displayed in the CollectionSpace browser, because the filter pane will extend the full height of the page, obscuring the left side of a full-width footer.

To add the CSS rules:

1. In the WordPress menu, select Appearance, then Customize, then Additional CSS.

1. Enter your CSS rules.

1. Click the Publish button.

Below is an example of CSS rules that may be used as a starting point for integrating with the Twentyseventeen WordPress theme:

```
.collectionspace-template-default .site-content {
	padding-top: 0;
}

.collectionspace-template-default .cspace-DetailPage--common {
	padding: 48px;
}

.collectionspace-template-default .cspace-DetailPanel--common {
	max-width: 910px;
}

.collectionspace-template-default .site-footer {
	margin-top: 0;
}

.cspace-Filter--common label {
	font-weight: inherit;
}

/*
  Reduce the width of the footer and move it right, to avoid having
  the filter panel cover it. (Only do this for screen widths where
  the filter panel is not a collapsible overlay.)
*/

@media only screen
and (min-width: 769px) {
	.has-cspace-SearchResultPage .site-footer {
		width: calc(100% - 260px);
		float: right;
	}
}
```

## Upgrading the Plugin

To upgrade to a new release of this plugin, follow the [installation instructions](#installing-the-plugin), using the zip file of the new release you want to use. All of your existing CollectionSpace Browser posts and configurations will be retained. Check the release notes for any special instructions pertaining to that release.

## Known Bugs

- Deep links to pages within the browser (for example, links to object detail pages, and links to filtered search results) occasionally stop working, and lead to a page not found error. A short-term fix for this is to re-save the WordPress post that contains the browser:

  1. Log in to the admin interface of your WordPress site.
  1. Click on CollectionSpace Browsers in the WordPress menu.
  1. In the table of posts, click the title of the post that you added.
  1. In the Publish section in the right sidebar, click the Update button.

  This should get deep links to work again.
