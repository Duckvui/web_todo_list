import { useState } from 'react'
import { taskProgress, taskStatus } from '../services/taskService'

export function HomeStats({ stats }) {
  return <section className="home-stats" aria-label="Thống kê công việc">{[['▦', 'Tổng công việc', stats.total, 'Tất cả kế hoạch của bạn'], ['◷', 'Cần làm hôm nay', stats.today, 'Tập trung vào hiện tại'], ['✓', 'Đã hoàn thành', stats.completed, 'Những bước tiến nhỏ']].map(([icon, label, count, note]) => <div className="home-card stat-card" key={label}><span className="stat-icon">{icon}</span><span>{label}</span><strong>{count.toString().padStart(2, '0')}</strong><small>{note}</small></div>)}</section>
}

function workUnits(tasks) {
  // A group contributes its actionable children, so parent and children are not counted twice.
  return tasks.flatMap((task) => task.subtasks.length ? task.subtasks : [task])
}

export function HomeOverview({ tasks, selectedDate, now }) {
  const [month, setMonth] = useState(() => selectedDate.slice(0, 7))
  const units = workUnits(tasks)
  const daily = units.filter((task) => task.dueDate.slice(0, 10) === selectedDate)
  const done = daily.filter((task) => task.completed).length
  const percent = daily.length ? Math.round(done / daily.length * 100) : 0
  const monthly = units.filter((task) => task.dueDate.slice(0, 7) === month)
  const completed = monthly.filter((task) => task.completed).length
  const late = monthly.filter((task) => taskStatus(task, now).kind === 'late').length
  const overdue = monthly.filter((task) => taskStatus(task, now).kind === 'overdue').length
  const monthlyPercent = monthly.length ? Math.round(completed / monthly.length * 100) : 0
  const mainTasks = tasks.filter((task) => task.dueDate.slice(0, 7) === month)
  const mainProgress = mainTasks.length ? Math.round(mainTasks.reduce((sum, task) => sum + taskProgress(task), 0) / mainTasks.length) : 0
  const dateLabel = new Date(`${selectedDate}T00:00:00`).toLocaleDateString('vi-VN')
  return <aside className="home-right">
    <section className="home-card progress-card"><span className="overline">TỪNG BƯỚC TIẾN LÊN</span><h2>Tiến độ trong ngày</h2><p className="daily-progress-date">Ngày {dateLabel}</p><div className="progress-ring" style={{ '--progress': `${percent}%` }} role="img" aria-label={`Ngày ${dateLabel}: hoàn thành ${percent}%`}><div><strong>{percent}%</strong><span>hoàn thành trong ngày</span></div></div><p><strong>{done}</strong> / {daily.length} việc đến hạn đã xong</p><div className="progress-caption">{daily.length ? 'Chọn ngày khác trên lịch để xem tiến độ.' : 'Ngày này chưa có việc đến hạn.'}<br />Việc lớn tính từng task con; việc nhỏ tính task chính.</div></section>
    <section className="home-card monthly-stats" aria-labelledby="monthly-stats-title"><h2 id="monthly-stats-title">Thống kê theo tháng</h2><label>Chọn tháng<input type="month" value={month} onChange={(event) => { if (event.target.value) setMonth(event.target.value) }} /></label><div className="monthly-percent"><strong>{monthlyPercent}%</strong><span>việc đến hạn đã hoàn thành</span></div><progress max="100" value={monthlyPercent} aria-label={`Tiến độ tháng ${month}: ${monthlyPercent}%`} /><dl>{[['Tổng việc đến hạn', monthly.length], ['Đã hoàn thành', completed], ['Trong đó hoàn thành muộn', late], ['Chưa hoàn thành', monthly.length - completed], ['Trong đó đã quá hạn', overdue]].map(([label, value]) => <div key={label}><dt>{label}</dt><dd>{value}</dd></div>)}</dl>{!monthly.length && <p className="task-helper">Tháng này chưa có việc đến hạn.</p>}<p className="task-helper">Tính theo hạn của từng việc trong tháng đã chọn. Task chính có task con không được đếm thêm lần nữa.</p><div className="monthly-main-summary"><strong>{mainTasks.filter((task) => task.completed).length}/{mainTasks.length} task chính đã xong</strong><p className="task-helper">Tiến độ trung bình task chính đến hạn trong tháng: {mainProgress}%.</p></div></section>
    <p className="local-storage-note">Công việc lưu trên trình duyệt này theo tài khoản, chưa đồng bộ giữa các thiết bị.</p>
  </aside>
}
