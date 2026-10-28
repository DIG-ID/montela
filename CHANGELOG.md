# Changelog

All notable changes to this theme are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/)
and this project adheres to [Semantic Versioning](https://semver.org/) (`MAJOR.MINOR.PATCH`).

- **MAJOR** (`1.0.0` → `2.0.0`) — structural or breaking changes (redesign, feature removal, changes requiring manual intervention).
- **MINOR** (`1.0.0` → `1.1.0`) — new features / implementations (new template, new CPT, new section).
- **PATCH** (`1.0.0` → `1.0.1`) — bug fixes and small adjustments that do not add functionality.

---

## [1.20.0] — 2026-10-28

### Added
- Angebote & Packages page template, with the hero and intro sections.
- Offers post type ("Angebote & Packages"), under /angebote-packages/{offer}/, and its single page: hero, a "back to the overview" link and an intro with a price box and booking button.

### Changed
- Hero: the last title line is now the italic one, also when there are only two lines.

## [1.19.0] — 2026-10-28

### Added
- Aktivitäten page: summer and winter sections with the season's activities (a slider in pages of four when there are more than four), and a "Saas-Grund als Ausgangspunkt" section.
- Text with photos module, made from the homepage apartments section: title, text and button beside one or two photos — the layout adapts to the number of photos. The homepage keeps its content.

## [1.18.0] — 2026-10-28

### Added
- Aktivitäten page template (a child page of Erkunden), with the hero and intro sections.

### Changed
- Activity pages now live under the Aktivitäten page: /erkunden/aktivitaeten/{activity}/. Old /aktivitaeten/{activity}/ links redirect to the new address.

## [1.17.0] — 2026-10-28

### Added
- Unsere Region page template (a child page of Erkunden): hero, intro, a Saas-Grund section with photos and an icon list, highlights, a SaastalCard band and activities.
- Cards with icon module: a slider of framed cards, each with an icon, a title, a short line and a text.
- Box text module: a "Green band" style — a full-width sage band — next to the framed box.

## [1.16.0] — 2026-10-28

### Added
- Erkunden page: region section (title, text, button and up to three photos — the large one on desktop only), activities and a box text section.
- Box text module: a framed block with a small italic title, a title and a text, reusable on any page.

## [1.15.0] — 2026-10-28

### Added
- Erkunden (Explore) page template, with the hero and intro sections.
- Hero: a "Full screen" option, so pages other than the homepage can fill the screen on desktop, and a "Custom" position with an indent in px for each title line.

### Changed
- Sticky call-to-action: its title is now a heading (h2).

## [1.14.0] — 2026-10-28

### Added
- Sticky call-to-action on every page: a corner button that opens a card with a title, text and a button (Theme Settings → Sticky CTA). Closes with its arrow, Esc or a click outside, and hides while the footer is on screen.

### Fixed
- Homepage activities: the frame line no longer touches the season switch on desktop.

## [1.13.0] — 2026-10-27

### Added
- Homepage hero title animation on desktop: after a few seconds the title wipes away line by line and comes back smaller in the bottom-left corner. Visitors who prefer reduced motion see the final layout straight away.

## [1.12.1] — 2026-10-27

### Fixed
- Footer on tablet: the call-to-action box and text keep the design's width at every tablet size.

## [1.12.0] — 2026-10-27

### Added
- Testimonials: new post type (author and quote) to manage guest reviews in one place.
- Testimonials module on the homepage: a framed slider of the selected reviews, which cross-fade, with arrows.

### Changed
- Text keeps the arrow cursor on hover (it stays selectable); typing fields keep the text cursor.

## [1.11.1] — 2026-10-27

### Fixed
- Footer on tablet: newsletter and contact details stay side by side at every tablet width.
- Footer on mobile, now built to the design: full-width call-to-action box, everything centred, the footer menu as two columns of four, partner logos one per row and the legal links above the copyright.

## [1.11.0] — 2026-10-27

### Added
- Activities module on the homepage: title, text and button beside up to four activity cards, with a Sommer/Winter switch that swaps the cards (the selection slides between the two seasons). Winter opens by default from December to April.
- Activity cards: card image and title; on hover (always on touch screens) they open to show the excerpt and a link to the activity. One card at a time on mobile, with arrows.
- "Summer activities" and "Winter activities" fields to pick up to four cards per season; left empty, the first four activities of the season are shown.

## [1.10.0] — 2026-10-27

### Added
- Header on desktop, tablet and mobile: logo, main menu (desktop), "Book now" button, language switcher and menu button. It sits over the hero and stays in place below the admin bar.
- WPML language switcher: the current language in a box that opens the other languages one after another.
- Mega menu, opened by the menu button: a full-screen panel with a photo and the "Mega Menu" links. Items with sub-pages open their own view with a back button. Animated opening and closing, the menu icon turning into a close icon, and the page behind locked while it is open. Built for desktop, tablet and mobile.
- Footer on desktop and tablet: call-to-action box, logo, newsletter (Mailchimp for WP), contact details, socials, footer navigation, partner logos, copyright and legal links.
- "Theme Settings" options page with the header and footer content, and a new "Legal Menu" location.

### Changed
- The "Theme Settings" options page and its fields are defined in `acf-json/`: after deploying, run **Sync** in ACF → Options Pages and ACF → Field Groups.
- The theme logo (Montela, cream) is used when no custom logo is set.

## [1.9.0] — 2026-10-27

### Added
- Activity single page template, starting with the hero and intro modules, and an "Activity" field group (one tab per module).
- Hero: a desktop position for each title line (left, indented, centre or right), set in the editor.

### Changed
- Hero: the standard layout is now the inner-page one (900px tall on desktop, all lines the same size, last line in italic). The homepage keeps its full-screen hero as an exception.

## [1.8.0] — 2026-10-27

### Added
- "Activities" custom post type (`/aktivitaeten/…`), registered through ACF: title, card image, excerpt and revisions; content built from ACF modules.
- "Seasons" taxonomy for activities (Sommer / Winter), used to filter them.

## [1.7.0] — 2026-10-27

### Added
- Image band module: a full-width photo between sections, cropped per screen size.
- "Module — Image band" field group for cloning, and an Image band tab on the homepage.

## [1.6.0] — 2026-10-27

### Added
- Explore module: sage text panel (title, small photo, text, button) beside a photo slider; slider on top on tablet and mobile.
- "Module — Explore" field group for cloning, and an Explore tab on the homepage.
- Sage slider arrows for use over photos.

## [1.5.0] — 2026-10-27

### Added
- Homepage "Find your apartment" section: title, text, button and two photos.
- Homepage "Direct booking" section: benefits list with icons on a sage background.
- Homepage "Premium Hotel Stay" section: photos, services list with icons and a full-width photo slider with arrows.
- Reusable icon list component (icon, bold title, text) shared by the homepage sections.
- Homepage field group tabs for the three new sections.

### Changed
- Slider arrows can sit outside the slider element (e.g. below it on mobile).
- The Swiper slider is now loaded on the homepage.

## [1.4.0] — 2026-10-27

### Added
- Hero module: full-screen background image with an optional looping video and a three-line page title.
- Intro module: centred title and lead text over the mountains line drawing.
- ACF field groups as Local JSON (`acf-json/`): "Module — Hero" and "Module — Intro" for cloning, and "Page — Home" with one tab per section. All groups use WPML Expert mode (images/video copied, text translated).

### Changed
- The homepage template is now built from the hero and intro modules.

### Removed
- Starter example hero section on the homepage.

## [1.3.0] — 2026-10-27

### Added
- Layout grid: `.theme-container` (1820px max content width, 32/64/50px side margins) and `.theme-grid` (2/6/12 columns, 20px gutter).
- Buttons from the design system: `.btn-primary`, `.btn-secondary` and `.btn-outline` text buttons with arrow, and `.btn-icon` square buttons (arrow right, arrow down, home), with hover and keyboard focus states.
- Reusable button components: `template-parts/components/button.php` (takes an ACF Link field) and `button-icon.php`.
- "Design System" page template showing the theme's colours, typography classes and buttons.

### Changed
- SVG icons are served as separate cached files instead of being inlined into the main stylesheet.

### Removed
- Unused Google Fonts (Inter) default URL.

## [1.2.0] — 2026-10-27

### Added
- Typography classes from the design system: `.title-h1`–`.title-h4`, `.title-intro`, `.body-intro`, `.body-quote`, `.body-text` and `.body-small`, with mobile, tablet and desktop sizes.

### Changed
- Theme fonts set to FreightBig Pro (headings) and Roboto (body) via Adobe Fonts.
- Default body text now follows the design system's text style.

## [1.1.0] — 2026-10-26

### Changed
- JavaScript split into a small critical bundle (`main.js`) and separate, non-critical bundles, so WP Rocket can delay everything except `main.js`.
- GSAP, ScrollTrigger and Lenis moved to their own `animations.js` bundle.
- Swiper, Fancybox and Isotope are now optional modules, loaded (with their CSS) only on pages that use them via `montela_enqueue_module()`.
- Swiper and Fancybox CSS removed from the main stylesheet.
- The theme no longer loads jQuery for its main script.

### Fixed
- Google Maps init script now declares its jQuery dependency.

## [1.0.0] — 2026-10-26

### Added
- Project bootstrapped from dig.id Theme Starter Vite v2.0.2.
- Montela colour palette (cream, sage, mustard, walnut) in Tailwind and CSS custom properties.
- Navigation menus: Main Menu, Mega Menu, Footer Menu.
- Coding conventions for Claude Code in `.claude/rules/` (PHP, SASS, JS, editor content, Figma grid), taken from Theme Starter Vite v2.3.0.
