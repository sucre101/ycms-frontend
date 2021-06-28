import AWN from 'awesome-notifications'

export default function access ({ next, store }) {

  const USER = store.state.auth.currentUser
  const MESSAGE = 'Access denied'
  const awn = new AWN({ position: 'top-right' })

  if (USER.has_owner !== null) {
    awn.alert(MESSAGE)
    return next({
      name: 'apps'
    })
  } else {
    return next()
  }

}