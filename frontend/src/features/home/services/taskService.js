const key = (userId) => `focusly:tasks:${userId}`
export const taskService = {
  list(userId) {
    const data = JSON.parse(localStorage.getItem(key(userId)) || '[]')
    if (!Array.isArray(data) || data.some((t) => !t || typeof t.id !== 'string' || typeof t.title !== 'string' || typeof t.completed !== 'boolean' || typeof t.dueDate !== 'string')) throw new Error('Dữ liệu công việc không hợp lệ.')
    return data
  },
  save(userId, tasks) { localStorage.setItem(key(userId), JSON.stringify(tasks)) },
  create({ title, dueDate, priority }) {
    const cleaned = title.trim()
    if (!cleaned || cleaned.length > 160) throw new Error('Tên công việc cần từ 1 đến 160 ký tự.')
    return { id: crypto.randomUUID(), title: cleaned, dueDate, priority, completed: false }
  },
}
export function localDateKey(date = new Date()) {
  return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`
}
