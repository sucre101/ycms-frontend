export const paymentServices = {
  1: {
    id: 1,
    label: 'Stripe',
    logo: '@/assets/img/payment_services/Stripe.png',
    fields: [
      { name: 'Published key' },
      { name: 'Secret key' },
    ]
  },

  2: {
    id: 2,
    label: 'PayPal',
    fields: []
  }
}

export const styleTypes = [
  { type: 'range', name: 'width', title: 'Element width', min: 5, max: 100 },
  { type: 'range', name: 'height', title: 'Element height', min: 1, max: 100 },
  { type: 'range', name: 'border-radius', title: 'Element radius', min: 0, max: 20 },
  { type: 'range', name: 'border-width', title: 'Border', min: 0, max: 5 },
  { type: 'range', name: 'font-size', title: 'Font size', min: 5, max: 24 },
  { type: 'range', name: 'margin-left', title: 'Horizontal margin', min: -100, max: 100 },
  { type: 'range', name: 'margin-top', title: 'Vertical margin', min: -100, max: 100 },

  { type: 'select',
    name: 'justify-content',
    title: 'Element text position horizontal',
    variants: ['flex-start', 'flex-end', 'center']
  },
  { type: 'select',
    name: 'align-items',
    title: 'Element text position vertical',
    variants: ['flex-start', 'flex-end', 'center']
  },
  { type: 'color', name: 'color', title: 'Font color' },
  { type: 'color', name: 'background-color', title: 'Background color' },
  { type: 'color', name: 'border-color', title: 'Border color'},
]

export const userFields = {
  1: {
    type_id: 1,
    title: 'Registration link'
  },
  2: {
    type_id: 2,
    title: 'User avatar'
  },
  3: {
    type_id: 3,
    title: 'Phone number'
  },
  4: {
    type_id: 4,
    title: 'Email'
  },
  5: {
    type_id: 5,
    title: 'Website link'
  },
  6: {
    type_id: 6,
    title: 'Extended address'
  }
}
