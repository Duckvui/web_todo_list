import { navigateTo } from '../../app/useRoute'
export function AppLink({ to, children, ...props }) {
  return <a href={to} onClick={(event) => { event.preventDefault(); navigateTo(to) }} {...props}>{children}</a>
}
