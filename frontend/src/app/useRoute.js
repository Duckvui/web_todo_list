import { useCallback, useEffect, useState } from 'react'

export function navigateTo(path, { replace = false, state } = {}) {
  window.history[replace ? 'replaceState' : 'pushState'](state ?? {}, '', path)
  window.dispatchEvent(new PopStateEvent('popstate'))
}

export function useRoute() {
  const [path, setPath] = useState(window.location.pathname)
  useEffect(() => {
    const update = () => setPath(window.location.pathname)
    window.addEventListener('popstate', update)
    return () => window.removeEventListener('popstate', update)
  }, [])
  return { path, navigate: useCallback((...args) => navigateTo(...args), []) }
}
