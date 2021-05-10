const rulesStyleElementExclude = {

  checkout_btn: [],

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
    'font-size': '16'
  },

  bottom_nav: {
    'background-color': '#3280ec',
    'color': '#ffffff',
    'height': '30',
    'font-size': '16',
    'width': '100%'
  },


}

module.exports = {
  rulesStyleElementExclude,
  baseStyleTemplate
}
