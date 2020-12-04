let subjectsExample = [
  // subject (data variable) and ref element name are same
  {subj: 'name', message: 'Please fill shop name field'},
  // subjects as array, ref is element or component
  {
    subj: ['address', 'city', 'country', 'lat', 'lon'],
    ref: 'addrSearcher',
    message: `Address is not valid. Try to fill it again and select one
    of suggested variants`
  }
]

export default {
  methods: {
    verify(subjects) {
      let setInputValidity = (ref, message, isValid) => {
        let el = eval(ref)
        let classes = type(el) == 'object' ? el.$el.classList : el.classList
        let operator = isValid ? 'remove' : 'add'
        classes[operator]('error')
        if (!isValid) this.notifier.warning(message)
      }

      // eval('this') returns undefined if used as vue-mixin FYI
      let validate = fields => {
        try {
          fields.forEach(f => {
            let valid = false

            if (type(f.subj) == 'string')
            {
              valid = !!eval(f.subj)
            } else if (type(f.subj) == 'array')
            {
              valid = f.subj.every(el => !!eval(el))
            }

            let ref = f.ref || f.subj
            setInputValidity(ref, f.message, valid)

            if (valid == false) throw('Not valid')
          })
        } catch(err) {
          // console.error(err);
          return false
        }
        return true
      }

      return validate(subjects)
    }
  }
}
