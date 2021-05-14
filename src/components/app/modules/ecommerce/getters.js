const rulesStyleElementExclude = {

  checkout_btn: [],

  top_bar: [
    'width',
    'height',
    'align-items',
    'justify-content',
    'border-width',
    'border-color',
    'border-style',
    'border-radius',
    'margin-left',
    'margin-top'
  ],

  bottom_nav: [
    'width',
    'height',
    'align-items',
    'justify-content',
    'border-width',
    'border-color',
    'border-style',
    'border-radius'
  ]

}

const baseStyleTemplate = {

  checkout_btn: {
    title: 'Checkout',
    css: {
      'display': 'flex',
      'border-radius': '0',
      'background-color': '#3280ec',
      'color': '#ffffff',
      'width': '100',
      'height': '30',
      'justify-content': 'center',
      'align-items': 'center',
      'border-width': '0',
      'border-color': '#ffffff',
      'border-style': 'solid',
      'font-size': '16',
      'margin-left': '0',
      'margin-top': '0',
      'position': 'absolute'
    }
  },

  top_bar: {
    title: 'Top Bar',
    css: {
      'background-color': '#3280ec',
      'color': '#ffffff',
      'font-size': '16',
      'height': '40',
      'display': 'flex',
      'justify-content': 'center',
      'align-items': 'center'
    }
  },

  bottom_nav: {
    title: 'Bottom navigation',
    css: {
      'background-color': '#3280ec',
      'color': '#ffffff',
      'height': '40',
      'font-size': '16',
      'width': '100%',
      'display': 'flex',
      'justify-content': 'center',
      'align-items': 'center',
    }
  },


}

const elementsPosition = {

  checkout_btn: {
    'position': 'absolute',
    'bottom': '50px',
    'left': '0',
    'width': '100%'
  },

  bottom_nav: {
    'width': '100%',
    'bottom': '0',
    'left': '0',
    'position': 'absolute',
    'height': '40px'
  },

  top_bar: {
    'width': '100%',
    'top': '0',
    'left': '0',
    'position': 'absolute',
    'height': '40px'
  }

}

module.exports = {
  rulesStyleElementExclude,
  baseStyleTemplate,
  elementsPosition
}
