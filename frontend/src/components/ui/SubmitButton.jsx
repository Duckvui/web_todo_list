export function SubmitButton({ loading, children, className = '' }) {
  return <button className={'button button-primary ' + className} type="submit" disabled={loading}>{loading && <span className="spinner" />}{loading ? 'Đang xử lý...' : children}</button>
}
