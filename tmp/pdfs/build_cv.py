from reportlab.pdfgen import canvas
from reportlab.lib.colors import HexColor
from reportlab.pdfbase import pdfmetrics
from reportlab.pdfbase.ttfonts import TTFont
from reportlab.platypus import Paragraph
from reportlab.lib.styles import ParagraphStyle
from reportlab.lib.enums import TA_LEFT
from xml.sax.saxutils import escape

pdfmetrics.registerFont(TTFont('Arial', 'C:/Windows/Fonts/arial.ttf'))
pdfmetrics.registerFont(TTFont('Arial-Bold', 'C:/Windows/Fonts/arialbd.ttf'))
pdfmetrics.registerFontFamily('Arial', normal='Arial', bold='Arial-Bold')
out='C:/todoapp/output/pdf/CV_Web_Developer_Intern_TranVanDuc.pdf'
c=canvas.Canvas(out, pagesize=(595.28,841.89))
c.setTitle('Tran Van Duc - Web Developer Intern')
c.setAuthor('Tran Van Duc')
left=42; width=511; y=801
style=ParagraphStyle('body',fontName='Arial',fontSize=9.5,leading=13.3,textColor=HexColor('#263345'))
def p(text, size=9.5, bold=False, after=4):
    global y
    s=ParagraphStyle('x',parent=style,fontSize=size,leading=size*1.38,fontName='Arial-Bold' if bold else 'Arial')
    a=Paragraph(text,s); w,h=a.wrap(width,800); a.drawOn(c,left,y-h); y-=h+after
def section(t):
    global y
    y-=7
    p(t,10.3,True,4)
    c.setStrokeColor(HexColor('#CED8E4')); c.setLineWidth(.6); c.line(left,y+1,left+width,y+1); y-=5
def bullet(t): p('• '+t,after=2)
def link(label,url): return '<link href="'+url+'" color="#155A86">'+label+'</link>'
p('TRẦN VĂN ĐỨC',23,True,1)
p('WEB DEVELOPER INTERN | PHP / LARAVEL &amp; REACTJS',10.5,True,6)
p('Hà Nội  |  0947 137 362  |  '+link('ducphochu123@gmail.com','mailto:ducphochu123@gmail.com'),9.3,after=2)
p(link('github.com/Duckvui','https://github.com/Duckvui')+'  |  Có thể làm toàn thời gian từ thứ Hai đến thứ Sáu',9.3,after=4)
section('GIỚI THIỆU')
p('Sinh viên Kỹ thuật phần mềm có kinh nghiệm phát triển website với Laravel, ReactJS và MySQL qua 2 dự án. Đã xây dựng giao diện, REST API, phân quyền người dùng và triển khai ứng dụng lên VPS. Có kinh nghiệm kiểm thử chức năng, hỗ trợ xử lý lỗi và vận hành phần mềm thực tế. Mong muốn phát triển lâu dài trong lĩnh vực Web Development và tham gia xây dựng, bảo trì website doanh nghiệp.')
section('KỸ NĂNG')
p('<b>Phát triển web:</b> PHP, Laravel, Eloquent ORM, REST API, JWT; JavaScript, ReactJS, HTML, CSS.',after=2)
p('<b>Cơ sở dữ liệu:</b> MySQL, SQL Server; thiết kế cơ sở dữ liệu.',after=2)
p('<b>Công cụ &amp; triển khai:</b> Git, Postman, Linux, Ubuntu VPS, Nginx, PHP-FPM.',after=2)
p('<b>Thiết kế &amp; AI:</b> Đã sử dụng Figma và công cụ AI trong cả hai dự án web bên dưới.',after=2)
section('DỰ ÁN NỔI BẬT')
p('Hệ thống quản lý phòng khám | Full-stack Developer',10,True,1)
p('03/2026 - 07/2026  |  Laravel, ReactJS, MySQL, JWT, Ubuntu VPS',9,after=3)
bullet('Xây dựng giao diện và REST API cho quản lý bệnh nhân, bác sĩ, đặt lịch, khám bệnh, đơn thuốc, thanh toán và chat.')
bullet('Thiết kế cơ sở dữ liệu MySQL; triển khai xác thực JWT, mã hóa mật khẩu và phân quyền theo vai trò.')
bullet('Triển khai hệ thống lên Ubuntu VPS với Nginx, PHP-FPM, MySQL và React production build.')
p(link('GitHub: Duckvui/do_an_tot_nghiep','https://github.com/Duckvui/do_an_tot_nghiep')+'  |  '+link('Xem demo','http://163.227.231.106:8081'),9,after=8)
p('Web chia sẻ cảm xúc | Full-stack Developer',10,True,1)
p('08/2026 - 09/2026  |  Laravel, ReactJS, MySQL, REST API',9,after=3)
bullet('Phát triển giao diện, API và xử lý dữ liệu cho bài viết, story, bình luận, nhắn tin, kết bạn và theo dõi người dùng.')
bullet('Xây dựng Avatar/Pet thay đổi theo cảm xúc và chức năng hai người cùng tương tác với Pet chung.')
bullet('Triển khai ứng dụng lên VPS để chạy thực tế.')
p(link('GitHub: Duckvui/ket_noi_cam_xuc','https://github.com/Duckvui/ket_noi_cam_xuc')+'  |  '+link('Xem demo','http://163.227.231.106'),9,after=3)
section('KINH NGHIỆM LÀM VIỆC')
p('ISOFH | Chuyên viên triển khai và vận hành dự án',10,True,1)
p('2025 - 2026',9,after=2)
bullet('Triển khai, vận hành hệ thống HIS tại cơ sở y tế; kiểm thử chức năng và hỗ trợ xử lý lỗi trong quá trình sử dụng.')
bullet('Hướng dẫn người dùng và phối hợp với các bộ phận liên quan để xử lý sự cố.')
p('Blueco Toàn Cầu | Quản trị L1 thuê TMS',10,True,1)
p('2026',9,after=2)
bullet('Tiếp nhận, theo dõi ticket; kiểm tra và phân tích vấn đề trong phạm vi L1, phối hợp xử lý lỗi chuyên sâu.')
section('HỌC VẤN')
p('Đại học Thủy Lợi | Kỹ thuật phần mềm',10,True,1)
p('2022 - 2026  |  GPA: 3.25/4.00',9.5,after=0)
assert y>30, y
c.save()
print(out)
print('Bottom position:', y)

