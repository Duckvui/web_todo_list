export function FormStatus({ error, success }) {
  if (error) return <div className="alert alert-error" role="alert">{error}</div>
  if (success) return <div className="alert alert-success" role="status">{success}</div>
  return null
}
