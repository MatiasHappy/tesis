/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        'duty': '#3a7d84',  // Example blue color for tasks
        'habit': '#5dddb7',
        'fun': '#38a6b3',
        
        'dutyLight': "#256f77",


        'morning' : '#E6E600',
        'afternoon' : '#DAA520',
        'evening' : '#483D8B',
        'night' : '#4B0082'
      },
      fontFamily: {
        'NovecentoBookRegular': ['Novecento Book Regular', 'sans-serif'],
        'NovecentoMedium': ['Novecento Medium', 'sans-serif'],
        'NovecentoRegular': ['Novecento Regular', 'sans-serif'],
        'NovecentoCondLight': ['Novecento Cond Light', 'sans-serif'],
        'NovecentoCondRegular': ['Novecento Cond Regular', 'sans-serif'],
        'NovecentoCondBold': ['Novecento Cond Bold', 'sans-serif'],
        'SourceSans': ['source sans', 'sans-serif'],
      },
      backgroundImage: {
        'loginBg': "url('./src/assets/imgs/dutyBg.jpg')",  // Update the path to your image
      },
      fontWeight: {
        '300': '300',
        '400': '400',
        '500': '500',
        '600': '600',
        '700': '700',
        '800': '800',
      },
    },
  },
  plugins: [],
}

