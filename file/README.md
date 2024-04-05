# File handling

### 1. Include, require, require_once, include_once

- Include: Nếu file không tồn tại sẽ báo lỗi và tiếp tục thực hiện các đoạn mã ở phía sau

- Require: Nếu file không tồn tại sẽ báo lỗi và dừng lại

- Include_once: Tương tự như include nhưng nó sẽ include file đó một lần duy nhất

- Require_once: Tương tự như require nhưng nó sẽ require file đó một lần duy nhất

### 2. File handling functions

- fopen(): Sử dụng để mở một file.

- fclose(): Sử dụng để đóng một file.

- fwrite(): Sử dụng để ghi vào một file.

- fread(): Sử dụng để đọc một file.

- fgets(): Sử dụng để đọc một dòng trong file.

- feof(): Sử dụng để kiểm tra xem file đã kết thúc hay chưa.

- fgetc(): Sử dụng để đọc một ký tự trong file.

- file_get_contents(): Sử dụng để đọc toàn bộ nội dung của file và trả về một chuỗi.

- file_put_contents(): Sử dụng để ghi nội dung vào file.

- file_exists(): Sử dụng để kiểm tra xem file có tồn tại hay không.

### 3. File handling modes

- r: Mở một file để đọc. File pointer bắt đầu từ đầu của file.

- r+: Mở một file để đọc và ghi. File pointer bắt đầu từ đầu của file.

- w: Mở một file để ghi. File pointer bắt đầu từ đầu của file và làm mất nội dung của file. Nếu file không tồn tại, tạo một file mới.

- a: Mở một file để ghi. File pointer bắt đầu từ cuối của file. Nếu file không tồn tại, tạo một file mới.

- x: Tạo một file để ghi. Nếu file đã tồn tại, hàm fopen() sẽ thất bại bằng cách trả về FALSE và tạo ra một lỗi cấp E_WARNING. Nếu file không tồn tại, tạo một file mới.