# Changelog

All notable changes to this theme are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/)
and this project adheres to [Semantic Versioning](https://semver.org/) (`MAJOR.MINOR.PATCH`).

- **MAJOR** (`1.0.0` → `2.0.0`) — structural or breaking changes (redesign, feature removal, changes requiring manual intervention).
- **MINOR** (`1.0.0` → `1.1.0`) — new features / implementations (new template, new CPT, new section).
- **PATCH** (`1.0.0` → `1.0.1`) — bug fixes and small adjustments that do not add functionality.

---

## [1.57.1] — 2026-11-13
### Fixed
- Login screen and wp-admin: the Montela styling (logo, form, buttons, colours) applies again on WordPress 7.1, where core's own styles had taken over.
- Login screen: PHP notices printed by plugins no longer push the login form aside.
- WordPress error pages (such as "really log out?" and expired links) take the login look instead of WordPress's plain white page.

### Changed
- wp-admin: cream background, walnut for WordPress's own buttons, links and focus rings, menu, admin bar and list-table stripes in shades of the theme's sage, sage for the current item and hover states, with dark text where cream didn't reach 4.5:1 contrast. Boxes, tables, tabs, ACF tabs and button groups and the menu's update counts take the Montela palette instead of WordPress's blue-greys. The dig.id welcome widget keeps the agency's turquoise buttons.

## [1.57.0] — 2026-11-13

### Added
- Homepage hero with an image only (no video): the title lines are revealed left to right on page load. To be reviewed by the team.

## [1.56.2] — 2026-11-13

### Changed
- Map strip: popups open beside the pin on desktop, and just off it (diagonally) on tablet and mobile, so the pin stays visible.

## [1.56.1] — 2026-11-13

### Added
- Lounge & Bar and Shop: the map strip before the footer, as on Kids Playground.

## [1.56.0] — 2026-11-13

### Added
- Map strip module: a full-width, cropped view of the illustrated map with its pins and popups, and the summer / winter switch top right — no zoom or pan. On Kids Playground, before the footer. Its illustrations and pins live in Theme Settings, tab Map strip (empty = the Map tab's). After deploying, run the ACF Sync for Options — Theme Settings.

### Changed
- Map: the summer / winter switch is smaller on tablet and mobile.

## [1.55.2] — 2026-11-12
### Added
- Shop, producer cards: an optional "Mehr erfahren" button.

## [1.55.1] — 2026-11-12
### Added
- Leistungen, "Alles direkt im Resort": an optional "Mehr erfahren" button on each card.

## [1.55.0] — 2026-11-12

### Removed
- Der Montela Weg: the closing section ("Erleben Sie Montela selbst") and its fields. After deploying, run the ACF Sync for Page — Montela Way.

## [1.54.8] — 2026-11-12

### Changed
- Explore as the last section: the cream gap above the footer runs the full width again (under the slider too).

## [1.54.7] — 2026-11-12

### Changed
- Explore as the last section (Bike Hotel, day trips): a cream gap between the sage panel and the footer on desktop; the slider still runs into the footer.

## [1.54.6] — 2026-11-12
### Changed
- Apartment pages: "Inklusive Leistungen" has 192px of space above and below on desktop.

## [1.54.5] — 2026-11-12

### Changed
- Box text (box style — Kids Playground, Lounge & Bar, Shop, Erkunden): the text is vertically centred against the title on desktop.

## [1.54.4] — 2026-11-12

### Changed
- Explore section without the small photo (Bike Hotel): less space between the title and the text, more between the button and the footer's booking box (desktop and tablet).

## [1.54.3] — 2026-11-12
### Added
- Two Tailwind breakpoints for large screens: `3xl` (1920px) and `4xl` (2560px). Not used yet — no visible change.

## [1.54.2] — 2026-11-12
### Changed
- Der Montela Weg: the timeline is lower still on desktop (at least 620px); the photo now fills the panel's height instead of setting it.

## [1.54.1] — 2026-11-12
### Changed
- Der Montela Weg: the timeline is lower on desktop (at least 720px instead of 900px, the text centred between the title and the arrows) and on tablet (less space around the text).

### Removed
- Theme Settings: the Google Maps tab. The API key is now only set in `wp-config.php` (`MONTELA_GOOGLE_MAPS_KEY`).

## [1.54.0] — 2026-11-12
### Added
- Anreise & Lage: the map is now a Google map without hotels, cafés, shops or bus stops, with a Montela pin that shows the name and address on hover or tap. It needs a Google Maps API key (`MONTELA_GOOGLE_MAPS_KEY` in `wp-config.php`, or Theme Settings → Google Maps) and the Latitude / Longitude in the page's Location tab; without them, or if the key fails, the plain Google embed is shown as before.

## [1.53.1] — 2026-11-12
### Changed
- Apartments page: the apartment cards are lower on desktop — the text panel sets the height and the photo fills it — with tighter spacing in the panel.

## [1.53.0] — 2026-11-12
### Added
- Interactive map section on the homepage, Aufenthalt, Unsere Region and Unser Resort: illustrated summer / winter map with zoom, pan, fullscreen, a popup per location and a features bar below. The map artwork and its locations are set once in Theme Settings (tab Map); each page sets its own title, text, button and features.

### Changed
- Unsere Region: no extra space between the Highlights cards and the map. Aufenthalt: no extra space between the green band and the map.

## [1.52.11] — 2026-11-11
### Changed
- Text set at 18px on desktop (cards, icon lists, footer, H4, FAQ, forms and more) is 16/28 between 1280 and 1535px; 1536px and up unchanged.

## [1.52.10] — 2026-11-11
### Changed
- Smaller type between 1280 and 1535px: body text 16/28, intro text 20/36, H3 and intro titles 82/72, hero title line 1 120/95 and the other lines 93/81 on every hero. 1536px and up unchanged.

## [1.52.9] — 2026-11-11
### Changed
- Header: the menu button's hover is the menu's light cream box, instead of mustard bars.
- Mega menu: links straight to a page show an arrow on hover, as the sub-menu pages do.

## [1.52.8] — 2026-11-11
### Fixed
- Apartment facts (cards and apartment pages): "Badezimmer" has a bathroom icon instead of a bed.

### Changed
- Hero: the scroll-down icon fades out more gradually, over the first 400px of scrolling.

## [1.52.7] — 2026-11-11
### Changed
- Header menu: the first and last items have the same padding as the others.

## [1.52.6] — 2026-11-11
### Changed
- Hero: the scroll-down icon fades out over the first bit of scrolling and comes back at the top of the page.
- Hero: the scroll-down icon also shows on the homepage (on desktop, not while the title animation over a video runs).

## [1.52.5] — 2026-11-11
### Changed
- Icon lists: the text is 18/30 on desktop on every page — Anreise, Bike Hotel, Kids Playground, Fitnessraum, day trips and offers too (they were 15/25 or 17/23), and the bold titles of the Anreise and day-trip lists.

## [1.52.4] — 2026-11-11
### Changed
- Day trip pages: the destination highlights sit on the left on desktop.

## [1.52.3] — 2026-11-11
### Changed
- Intro text: 22/38 on tablet and desktop (was 30/40 and 24/40).
- Footer: text at 18/30 on desktop; menu links widen to fit it.

## [1.52.2] — 2026-11-11
### Changed
- Header menu: the hover box fills the menu edge to edge, without a shadow.

## [1.52.1] — 2026-11-11
### Changed
- Header menu: hover and the current page show a light cream box with a shadow, as in the mega menu, instead of mustard text.

## [1.52.0] — 2026-11-11
### Changed
- Typography: on desktop, text drawn at 20/32 is now 18/30 across the site.

## [1.51.2] — 2026-11-11
### Changed
- Mega menu: on desktop, "Zurück" comes after the sub-menu pages.

## [1.51.1] — 2026-11-11
### Changed
- Homepage hero: the title animation plays only when the hero has a video; with just an image, the title stays in place.

## [1.51.0] — 2026-11-11
### Added
- Apartment pages: an optional second booking button (e.g. for a second building), shown only when filled — beside the first on desktop, below it on smaller screens.

### Changed
- Apartment pages, "Inklusive Leistungen": each field left empty takes the Apartments page's, so an apartment can change only its button, or only its list. The button and the list can now be edited on the apartment.
- Apartment pages: the intro text is a basic text editor (bold, italic, links, lists, line breaks); existing text keeps its paragraphs and line breaks.

## [1.50.9] — 2026-11-11
### Changed
- Mega menu: on desktop, "Zurück" and the parent page share the first row of the sub-menu, so it is as tall as the photo.

## [1.50.8] — 2026-11-11
### Changed
- Footer: on desktop, the columns spread across the full width, as designed.

## [1.50.7] — 2026-11-11
### Fixed
- Mega menu: on desktop, the photo no longer scrolls over the logo.

## [1.50.6] — 2026-11-11
### Changed
- Mega menu: on desktop, the sub-menu lists its pages in two columns, as the main menu does.
### Fixed
- Mega menu: long sub-menu labels stay on one line on mobile.

## [1.50.5] — 2026-11-11

### Fixed
- Apartment pages: the "Jetzt buchen" button is mustard, at the size of the design.

### Changed
- Leistungen: no space above the "Flexibel wohnen mit passenden Leistungen" section.

## [1.50.4] — 2026-11-11
### Changed
- Mega menu: the items start higher on mobile (120px) and tablet (140px).

## [1.50.3] — 2026-11-11
### Fixed
- Mega menu: on mobile, the items no longer scroll over the logo and the close button.

## [1.50.2] — 2026-11-11

### Changed
- Login screen: the Montela logo instead of the agency's.

## [1.50.1] — 2026-11-10

### Changed
- Mega menu: the photo only changes once the pointer or focus rests on an item for 250ms, so sweeping across the menu no longer flicks through the photos.

## [1.50.0] — 2026-11-10

### Added
- Mega menu: an image per menu item (Appearance → Menus); on desktop the photo crossfades to the hovered or focused item's image.

### Changed
- Theme Settings → Header → "Menu image" is now the default mega menu photo, shown when the menu opens and while no link is hovered.

## [1.49.2] — 2026-11-10

### Changed
- Intro: the text is at most 900px wide on desktop, the same on every page; back to 24/40.

### Fixed
- Intro: the title keeps its designed width (899px) on every desktop screen, so it breaks into lines as designed.

### Removed
- Intro: the "Desktop max width" field.

## [1.49.1] — 2026-11-10

### Changed
- Intro: the text is 35/55 on desktop, as designed (it was 24/40).

## [1.49.0] — 2026-11-10

### Added
- Intro: a "Desktop max width" field sets how wide the text can be on desktop.

### Changed
- Fitnessroom: the "Auf einen Blick:" title is set in the large serif (H3).

## [1.48.1] — 2026-11-10

### Changed
- Anreise & Lage: a shorter location section on desktop (less space around the address); the map no longer shows Google's address card, already in the panel beside it.

## [1.48.0] — 2026-11-10

### Added
- Theme Settings: a switch to show or hide the sticky CTA on every page.

### Changed
- Anreise & Lage: the "Direkt zu Montela" section shows a Google map (embedded, lazy-loaded) instead of the photo slider; the map can be set with Google's embed code, or follows the address.
- 404: the page fits the screen on desktop.

### Removed
- The unused Google Maps API loader (script, API key constant and ACF key setting).

## [1.47.0] — 2026-11-10

### Added
- Hero: an animated scroll cue at the bottom of every page except the homepage.

## [1.46.4] — 2026-11-10

### Changed
- Aktivitäten and Unsere Region: no extra space between the intro and the section after it.
- Impressum: the box titles line up on desktop.
- Text pages (Datenschutz, AGB…): less space above the content and before each section heading on desktop.

## [1.46.3] — 2026-11-10

### Changed
- Fitnessroom: the training cards' text sits lower, near the middle of the card, as in the outlined cards.

## [1.46.2] — 2026-11-10

### Changed
- Fitnessroom: the training cards' arrows hide when every card is in view.

## [1.46.1] — 2026-11-10

### Changed
- Sticky header: the current page's menu item is mustard, as in the normal header; 20px above and below on desktop; a smaller logo on mobile and tablet.
- Unser Resort: the hero title stays in place — the title animation is the homepage's only.
- Footer: the call-to-action box overlaps the sage through the layout itself, without a negative margin; it looks the same.

### Fixed
- Footer: the logo sits 68px below the call-to-action box on desktop, as designed (it was 120px).

## [1.46.0] — 2026-11-10

### Changed
- Hero: always full screen; the "Full screen" option is gone.
- Explore: the slider arrows only show with more than one photo.
- Day trips (Unsere Region): fewer than four cards are centred on desktop.

## [1.45.0] — 2026-11-09

### Added
- Apartment pages: "Inklusive Leistungen", the stay comparison and the offer cards can each have their own content per apartment; left empty, the Apartments page's content is shown.

## [1.44.2] — 2026-11-09

### Changed
- Mega menu: items with and without sub-menus start their labels at the same indent on every breakpoint.

## [1.44.1] — 2026-11-09

### Changed
- Footer (desktop): partner logos follow the other columns, 101px after the navigation, instead of sitting against the right margin; 64px between the two navigation columns; the legal links end 75px short of the margin on wide screens, as designed.
- Bike Hotel experiences (1280–1535px): wider cards (375px tall), the space between them growing from 20px to the designed 94px, and the right-hand copy on five columns.

## [1.44.0] — 2026-11-06

### Changed
- Every text field now accepts inline HTML: line breaks (also per breakpoint), bold, italics and links. Other markup is stripped.

## [1.43.2] — 2026-11-05

### Changed
- Fitnessroom: "Auf einen Blick" list spreads to the photo's height on tablet, with 30px icons; training cards keep the designed width (340px on tablet, 369px from 1536px).
- Photo sliders: the arrows sit on the grid — in the last column on the resort sub-pages.
- Bike Hotel: the experience arrows hide when every card is in view; the tablet arrow ground is as wide as the copy.
- Explore without photo as the last section: designed spacing above the footer box.
- Footer (desktop): partner logos on the same row as the rest, on the right; they shrink to fit between 1280 and 1535px.

## [1.43.1] — 2026-11-05

### Changed
- Shop products and producers sliders: the arrows hide when every card is in view (three or fewer on desktop).

## [1.43.0] — 2026-11-04

### Added
- Offer conditions: editable heading and validity label.

### Changed
- Offer conditions: defaults now "Konditionen" and "Gültigkeit des Angebotes:", as designed; on tablet the heading matches the validity line's size.

## [1.42.2] — 2026-11-04

### Changed
- Activities module (tablet): the frame line shows above the button and runs behind it, as designed.
- Testimonials (tablet): slider arrows further out, 45px from the frame.

## [1.42.1] — 2026-11-04

### Changed
- Activities module: on mobile, the sage arrows sit above the cards, as in the other activity sliders.

## [1.42.0] — 2026-11-04

### Added
- Aktivitäten page: choose whether the summer or the winter activities come first.

### Changed
- Text with photos (one photo): text and photo split in half on tablet, so the button fits.

## [1.41.0] — 2026-11-04

### Added
- Explore module: optional landscape slider photos for tablet, where the slider runs the full width; without them, tablet keeps the main photos.

## [1.40.1] — 2026-11-04

### Changed
- Activity cards on tablet and mobile: the photo sits above the panel instead of behind it, and the cards are taller, so more of the photo shows.

## [1.40.0] — 2026-11-03

### Added
- Structured data: the business as a Hotel (address, phone, e-mail, social profiles), offers as Offer (price, validity), apartments as Apartment (size, rooms, occupancy, amenities), activities and day trips as TouristAttraction.

### Changed
- Fitnessroom usage boxes: clearer field instruction for bold sub-heads.

### Fixed
- Footer booking box and sticky CTA titles are no longer headings, so they stay out of every page's heading outline.

## [1.39.2] — 2026-11-03

### Changed
- No custom field is required any more: pages, posts and the theme settings save with any field left empty, and the empty parts are simply not shown.

## [1.39.1] — 2026-11-03

### Changed
- Aufenthalt, Apartments, Premium Stay and Leistungen: field tabs named after what each section shows.

## [1.39.0] — 2026-11-03

### Added
- Leistungen page template: hero, intro, "Flexibel wohnen mit passenden Leistungen", "Lounge & Shop", "Frühstück" (photo strip and the dogs block), "Alles direkt im Resort", "Mehr Komfort nach Bedarf", the partner blocks and "Alles nah beieinander".
- Space module (from the Aufenthalt "Viel Raum" section), with a two-column desktop layout.
- Cards with icon: an optional button per card.
- Features: centred layout; an optional note and button per item.
- Horizontal: heading level and photo crop options.
- Box text: optional green button.

### Changed
- Resort sections: line breaks in the text are kept.

## [1.38.1] — 2026-11-02

### Fixed
- Offer cards (apartment single): title and excerpt centred, as drawn.
- Cards with icon: the "filled" style hides the slider arrows when every card is in view, like the "frame" style (the Fitnessroom keeps them, as drawn).

## [1.38.0] — 2026-11-02

### Changed
- Bike Hotel: the cards in "Bike-Erlebnisse" and "Lokales Know-how" use the activity card, as in the design — title in a bar that opens on hover on desktop, always open on tablet and mobile.

### Fixed
- Unser Resort: in the blocks with the photos on the left, the text keeps its gap to the right edge on screens narrower than 1920px.

## [1.37.0] — 2026-11-02

### Added
- Unsere Region, "Ausflüge ab Saas-Grund": the day trips have their own cards — photo on top, title, text and button always visible, as in the design. They no longer open on hover.

## [1.36.3] — 2026-11-02

### Fixed
- Box text (opening hours on Kids Playground, Lounge & Bar and Shop): a one-word title such as "Öffnungszeiten" no longer breaks before its last letter on desktop screens narrower than 1920px — the title and the box padding scale down with the screen.

## [1.36.2] — 2026-11-02

### Fixed
- Preview cards (day trips, offer cards, Bike Hotel): title, text and button label sit to the left, as in the design, instead of centred.
- Activity cards: the button label on hover sits to the left, as in the design.

## [1.36.1] — 2026-11-02

### Fixed
- Activities: the sage frame no longer shows through the cards while they fade in on the summer / winter switch.

## [1.36.0] — 2026-10-30

### Added
- Premium Stay page template: hero, intro, services comparison, "Ihr Apartment mit zusätzlichem Service" and "Lage & Resort".
- Text with photos: "Staggered" desktop layout (small photo above the title, the large one beside the title and text).

### Changed
- Services comparison: the text takes line breaks.

## [1.35.1] — 2026-10-30

### Changed
- Sticky header: the sage bar appears from the first scroll, instead of once the header has left the screen; scrolling back up, the bar folds up and the normal header drops back in, 50px before the top.

## [1.35.0] — 2026-10-30

### Added
- Sticky header: once the header scrolls out of view it comes back fixed at the top as a compact sage bar (80px on desktop and tablet, 76 on mobile), with the current menu item on a light background. Back at the top of the page, the normal header returns.

## [1.34.0] — 2026-10-30

### Added
- Apartment single page: back link, facts bar, photos, intro, amenities, gallery, and the shared "Inklusive Leistungen", services comparison and offer cards (read from the Apartments page).
- Offer cards module ("Für jede Auszeit das passende Angebot"), with an Offers tab on the Apartments page.

### Changed
- The "Zurück zur Übersicht" link is a shared component (offer and apartment singles).

### Fixed
- Tailwind now scans every root template, so classes set in `single-{post_type}.php` files reach the CSS.

## [1.33.2] — 2026-10-30

### Changed
- Unser Resort and its subpages (Bike Hotel, Kids Playground, Lounge & Bar, Meetings & Groups, Shop, Fitnessroom): section titles are h2 and titles inside a section h3, following the page structure (no skipped levels). Sizes are unchanged.

## [1.33.1] — 2026-10-30

### Fixed
- Bike Hotel cards: the default button label is "Mehr erfahren", like the other cards, instead of the English Figma placeholder.
- Sliders: what screen readers announce on the first and last slide and on the pagination dots is now in German (translatable), instead of Swiper's English messages.

## [1.33.0] — 2026-10-30

### Added
- Apartments: a new post type (one page per apartment, at /aufenthalt/apartments/…) with its fields — facts (size, bedrooms, bathrooms, occupancy), photos, intro with booking link, equipment and gallery.
- Amenities (Ausstattung) for apartments, each with its own icon.
- "Apartments Template" for the apartments overview page: hero, intro and the apartment cards — title, size, bathrooms, bedrooms and occupancy with icons, short text, "Mehr erfahren" and booking buttons, and a photo slider. The page can pick the apartments and their order; left empty, it shows them all. Below the cards: the services comparison, "Inklusive Leistungen" and the guest reviews slider.
- Box text module: a third style, "Cream band" — the green band's layout (title, text, button and a list with icons) in walnut on the page background.

### Changed
- The theme's own front-end texts (buttons such as "Mehr erfahren" and "Zurück zur Übersicht", labels, screen-reader texts, 404 fallbacks, footer copyright) now default to Swiss German; other languages are translated in WPML String Translation.

## [1.32.2] — 2026-10-30

### Fixed
- Section titles are `<h2>` again, side by side (homepage, Erkunden, Unsere Region, Aktivitäten, offers, day trips and the shared modules), so screen readers find every section; their look is unchanged.
- Small lines above a title ("SaastalCard", "Sommeraktivitäten") are no longer headings.
- Activity card titles are `<h3>` by default again.

## [1.32.1] — 2026-10-30

### Fixed
- Unsere Region: the icon list in the Saas-Grund section is spaced as before again.
- Homepage gallery ("Premium Hotel Stay"): the icons are back to their earlier size.
- Activity cards accept a heading level; the info cards of an activity single use `<h3>`, so the page no longer jumps from `<h2>` to `<h4>`.
- The title of the footer call-to-action box is an `<h2>` again: it no longer nests under the page's last section, and the 404 no longer jumps from `<h1>` to `<h3>`.

## [1.32.0] — 2026-10-30

### Added
- Unser Resort page ("Our Resort Template"): hero, intro and alternating text + photo sections (new Resort sections module).
- Six Resort subpages, each with its own template: Bike Hotel, Kids Playground, Lounge & Bar, Meetings & Groups, Shop and Fitnessroom.
- New modules: Green text (full-width sage band) and Horizontal (full-width photo, then title, button and text).
- Photo strip slider for the subpages, and info boxes for Meetings & Groups and Fitnessroom.
- Explore module: optional overline above the title, and a layout without the small photo.
- Cards with icon module: a "filled" style (sage cards, cream text and icons).

### Changed
- Heading tags now follow the Figma text styles (H2, H3, H4) instead of the page order; sizes are unchanged.
- Box text module: its margins can be set per page.
- Bullets in text lists are smaller.

## [1.31.0] — 2026-10-29

### Added
- Text pages (default page template — Datenschutz, AGB and any other page without a template): the title on a sage band and the editor content styled as running text (section titles, sub-headings, paragraphs, lists, links).
- "Impressum Template": the same title band and boxes with an icon, a title and a short text.

### Fixed
- Contact boxes: web addresses keep the text as typed ("www.dig.id", not "http://www.dig.id").

## [1.30.0] — 2026-10-29

### Added
- 404 page redesigned: a framed "404" with a short text and a button to the homepage, over the mountains drawing. Title, text and button label are edited in Theme Settings → 404.
- Dark header (walnut on cream) for pages without a hero image — used on the 404 page.

## [1.29.1] — 2026-10-29

### Changed
- FAQ: the arrow in each question turns into the cross (and back) with an animation.

## [1.29.0] — 2026-10-29

### Added
- FAQ page template ("FAQ Template"): hero, intro and the questions in groups, each an accordion.
- FAQ structured data on the FAQ page (FAQPage with its questions and answers), through Yoast SEO.

### Changed
- FAQ module: can also be filled from a list of groups (used by the FAQ page).

## [1.28.0] — 2026-10-29

### Added
- "Info & Kontakt" page template ("Contact Template"): hero, intro, contact boxes (phone numbers and e-mails are links), a Contact Form 7 contact form and an FAQ.
- FAQ module: title, text, button and an accordion of questions that opens and closes with an animation.

## [1.27.0] — 2026-10-29

### Added
- "Anreise & Lage" page template ("Arrival Template"): hero, intro, short distances, arriving by car and by train, SBB luggage service and "Direkt zu Montela" with the address and a photo slider of places nearby.
- Features module: a cream or sage band with a title, a text and features with icons.

## [1.26.0] — 2026-10-29

### Added
- Aufenthalt (Stay) page template: hero, intro, "Viel Raum für gemeinsame Ferien" (text, button, two photos and a list with icons), the services comparison, "Alles da, was Ferien einfacher macht" and the guest reviews slider.
- Box text module, green band style: an optional button under the title and a list with icons below (two columns on desktop).

### Changed
- Intro text on desktop is smaller (24px), on every page.
- Box text green band without the small title: the title now starts level with the text on desktop.
- Services comparison on tablet: the text is narrower and evenly spaced between the title and the button, as in the design (also on Angebote & Packages).

## [1.25.1] — 2026-10-29

### Changed
- Day trip pages: the last section (Explore) now runs on behind the footer's booking box, as in the design.

## [1.25.0] — 2026-10-29

### Added
- "Der Montela Weg" page template ("Montela Way Template"): hero, intro, values with photos, the Montela story, a year-by-year timeline slider, plans for the future and closing words.

## [1.24.0] — 2026-10-29

### Added
- Activity single: two text sections with a photo and a checklist, "Cards with icon", info cards and the Activities section.
- "Text with checklist" module: title, text, checklist and a photo on the left or right.
- Activity info cards: 2 to 4 cards written in each activity, with an optional button (a page, another site or a PDF).

### Changed
- Activities section on an activity: shows the other activities of the same season, without the switch.

## [1.23.4] — 2026-10-29

### Fixed
- "Ausflüge ab Saas-Grund" title: line breaks entered in the editor now show as line breaks.

## [1.23.3] — 2026-10-28

### Changed
- Preview cards on mobile and tablet: the photo shows whole, with the panel below it, and the description is always visible.

## [1.23.2] — 2026-10-28

### Changed
- The day trip card became a reusable preview card component (no visible change).

## [1.23.1] — 2026-10-28

### Changed
- Unsere Region day trip cards: closed by default (photo, title and link); on hover or keyboard focus the panel grows over the photo and shows the description. Always open on touch screens.

## [1.23.0] — 2026-10-28

### Added
- Unsere Region: "Ausflüge ab Saas-Grund" — a slider of day trip cards (card image, title, excerpt and a link), picked and ordered in the page or all day trips by default.

### Changed
- Day trips now live under the Unsere Region page: /erkunden/unsere-region/{trip}/. Old /tagesausflug/ links redirect to the new address.

## [1.22.0] — 2026-10-28

### Added
- Tagesausflüge (day trips) post type, under /tagesausflug/{trip}/, and its single page: hero, intro, the destination with highlights and a photo slider, how to get there, and what to experience.
- Cards with icon module: an optional text under the title.

## [1.21.0] — 2026-10-28

### Added
- Offer single: included items with icons, a photo slider, conditions with the offer's validity dates, and Text with photos.
- Angebote & Packages: offer cards built from the active offers (expired offers hide themselves), and a Services comparison module.

### Changed
- The homepage photo slider became a shared component, also used on the offer single.
- Consistent 20px spacing between columns, cards and slides across all sections.

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
