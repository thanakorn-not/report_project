<!-- ============================
    นักศึกษาออกกลางคัน
=============================== -->
<div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-4">
  <div class="col-span-4 bg-white p-4 rounded shadow">

    <h3 class="text-lg font-bold mb-4">ชื่อ-สกุล (นักศึกษาออกกลางคัน)</h3>

    <!-- คำนำหน้า -->
    <div class="mb-4">
      <label class="block font-semibold mb-1">คำนำหน้า</label>
      <div class="flex flex-wrap gap-4">
        <label><input type="radio" name="student_prefix" value="เด็กชาย" required class="mr-2"> เด็กชาย</label>
        <label><input type="radio" name="student_prefix" value="เด็กหญิง" class="mr-2"> เด็กหญิง</label>
        <label><input type="radio" name="student_prefix" value="นาย" class="mr-2"> นาย</label>
        <label><input type="radio" name="student_prefix" value="นาง" class="mr-2"> นาง</label>
        <label><input type="radio" name="student_prefix" value="นางสาว" class="mr-2"> นางสาว</label>
        <label><input type="radio" name="student_prefix" value="พระ" class="mr-2"> พระ</label>
        <label><input type="radio" name="student_prefix" value="สามเณร" class="mr-2"> สามเณร</label>
      </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

      <div>
        <label class="block font-semibold mb-1">ชื่อ</label>
        <input type="text" name="student_firstname" required class="border p-2 w-full rounded" placeholder="Firstname">
      </div>

      <div>
        <label class="block font-semibold mb-1">สกุล</label>
        <input type="text" name="student_lastname" required class="border p-2 w-full rounded" placeholder="Lastname">
      </div>

      <div>
        <label class="block font-semibold mb-1">รหัสนักศึกษา</label>
        <input type="text" name="student_code" required class="border p-2 w-full rounded" placeholder="Student Code">
      </div>

      <div>
        <label class="block font-semibold mb-1">ศกร.ตำบล</label>
        <input type="text" name="student_school" required class="border p-2 w-full rounded" placeholder="School">
      </div>
      <!-- ระดับชั้น -->
      <div class="mb-4">
        <label class="block font-semibold mb-1">ระดับชั้น</label>
        <div class="flex flex-wrap gap-4">
          <label><input type="radio" name="student_level" value="ประถมศึกษา" required class="mr-2"> ประถมศึกษา</label>
          <label><input type="radio" name="student_level" value="มัธยมศึกษาตอนต้น" class="mr-2"> มัธยมศึกษาตอนต้น</label>
          <label><input type="radio" name="student_level" value="มัธยมศึกษาตอนปลาย" class="mr-2"> มัธยมศึกษาตอนปลาย</label>
        </div>
      </div>

    </div>

  </div>
</div>


<!-- ============================
    ครูผู้สอน
=============================== -->
<div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-4">
  <div class="col-span-4 bg-white p-4 rounded shadow">

    <h3 class="text-lg font-bold mb-4">ชื่อ-สกุล (ครูผู้สอน)</h3>

    <!-- คำนำหน้า -->
    <div class="mb-4">
      <label class="block font-semibold mb-1">คำนำหน้า</label>
      <div class="flex flex-wrap gap-4">
        <label><input type="radio" name="teacher_prefix" value="นาย" required class="mr-2"> นาย</label>
        <label><input type="radio" name="teacher_prefix" value="นาง" class="mr-2"> นาง</label>
        <label><input type="radio" name="teacher_prefix" value="นางสาว" class="mr-2"> นางสาว</label>
      </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

      <div>
        <label class="block font-semibold mb-1">ชื่อ</label>
        <input type="text" name="teacher_firstname" required class="border p-2 w-full rounded" placeholder="Firstname">
      </div>

      <div>
        <label class="block font-semibold mb-1">สกุล</label>
        <input type="text" name="teacher_lastname" required class="border p-2 w-full rounded" placeholder="Lastname">
      </div>

    </div>

  </div>
</div>