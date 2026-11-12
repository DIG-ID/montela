/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    // Every root template, single-{post_type}.php included (classes passed
    // from there to template parts would otherwise never reach the CSS).
    './*.php',
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
      // Above Tailwind's 2xl (1536px), appended after it so the min-width
      // queries keep their order. CSS pixels: a 4K screen usually runs at
      // 150–200% scaling (1920–2560px), so nothing above 4xl.
      screens: {
        '3xl': '1920px', // the Figma desktop canvas
        '4xl': '2560px', // 1440p ("2K") at 100%
      },
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
