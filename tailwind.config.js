/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './404.php',
    './archive.php',
    './footer.php',
    './functions.php',
    './header.php',
    './index.php',
    './page.php',
    './single.php',
    './inc/**/*.php',
    './page-templates/**/*.php',
    './template-parts/**/*.php',
  ],
  theme: {
    fontFamily: {
      primary: ['roboto', 'sans-serif'],         // body — Adobe Fonts: 300, 300i, 400, 700
      secondary: ['freight-big-pro', 'serif'],  // headings — Adobe Fonts: 400, 400i, 500, 500i
    },

    extend: {
      letterSpacing: {
        // wide: '.038em',
        // wider: '.06em',
      },
      colors: {
        // project colors go here
        // example: 'brand-blue': '#1a3c5e',
        // montela colors
        'montela-cream': '#F8F8F2',
        'montela-sage': '#8F9B87',
        'montela-mustard': '#E2B529',
        'montela-walnut': '#4E3C2A',
      },
      transitionTimingFunction: {
        'out-expo': 'cubic-bezier(0.16, 1, 0.3, 1)',
      },
      gridTemplateRows: {
        masonry: 'masonry',
      },
    },
  },
  plugins: [],
};
