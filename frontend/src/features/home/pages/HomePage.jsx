import { useState } from 'react'
import { TaskCalendar } from '../components/TaskCalendar'
import { localDateKey } from '../services/taskService'
import { useAuth } from '../../auth/useAuth'
import { useHomeTasks } from '../hooks/useHomeTasks'
import { TaskComposer } from '../components/TaskComposer'
import { TaskList, TaskNotifications } from '../components/TaskList'
import { HomeOverview, HomeStats } from '../components/HomeOverview'
export function HomePage() {
  const { user } = useAuth()
  const home = useHomeTasks(user.id)
  const [selectedDate, setSelectedDate] = useState(() => localDateKey())
  const name = user.thong_tin_tai_khoan?.ho_ten || 'bạn'
  return <><header className="home-heading"><div><span className="overline">HÔM NAY LÀ MỘT KHỞI ĐẦU MỚI</span><h1>Chào {name} <span>☀</span></h1><p>Sắp xếp một chút. Tập trung hơn. Làm điều có ý nghĩa.</p></div><time dateTime={new Date().toISOString()}>{new Date().toLocaleDateString('vi-VN', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })}</time></header><HomeStats stats={home.stats} /><div className="home-columns"><div className="home-center">{home.error && <p className="alert alert-error" role="alert">{home.error}</p>}<p className="task-action-notice" role="status">{home.notice}</p><TaskCalendar tasks={home.tasks} selectedDate={selectedDate} onSelectDate={setSelectedDate} now={home.now} onToggle={home.toggleTask} /><TaskComposer onAdd={home.addTask} selectedDate={selectedDate} /><TaskList tasks={home.visibleTasks} filter={home.filter} onFilter={home.setFilter} query={home.query} onQuery={home.setQuery} onToggle={home.toggleTask} onDeadline={home.updateDeadline} now={home.now} /><TaskNotifications tasks={home.notifications} now={home.now} /></div><HomeOverview tasks={home.tasks} selectedDate={selectedDate} now={home.now} /></div></>
}



