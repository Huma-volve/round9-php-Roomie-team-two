
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


document.getElementById('saveHousingType').addEventListener('click', function() {
    let newHousingType = document.getElementById('newHousingType').value;

    if (newHousingType !== "") {
        // استخدام AJAX لإرسال البيانات وحفظها
        $.ajax({
            url: '/housing-type',  // قم بتعديل الرابط حسب الحاجة
            type: 'POST',
            data: {
                housing_type: newHousingType,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // إضافة الاختيار الجديد إلى المصفوفة
                housingOptions.push({
                    id: response.id,  // يجب أن يكون مطابقًا لاسم الـ ID الذي يعيده الخادم
                    housing_type: response.housing_type  // يجب أن يكون مطابقًا لاسم نوع السكن
                });

                // تحديث الـ <select>
                updateHousingTypeSelect();

                // إغلاق المودال
                $('#addHousingTypeModal').modal('hide');
                $('.modal-backdrop').remove();

                // إعادة تعيين حقل الإدخال
                document.getElementById('newHousingType').value = '';
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseText);
            }
        });
    } else {
        alert("الرجاء إدخال نوع السكن");
         // إغلاق المودال
        $('#addHousingTypeModal').modal('hide');
                $('.modal-backdrop').remove();
    }
});



document.getElementById('saveFamilyType').addEventListener('click', function() {
    let newFamilyType = document.getElementById('newFamilyType').value;

    if (newFamilyType !== "") {
        // استخدام AJAX لإرسال البيانات وحفظها
        $.ajax({
            url: '/family-type',  // قم بتعديل الرابط حسب الحاجة
            type: 'POST',
            data: {
                family_type: newFamilyType,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // إضافة الاختيار الجديد إلى المصفوفة
                familyOptions.push({
                    id: response.id,  // يجب أن يكون مطابقًا لاسم الـ ID الذي يعيده الخادم
                    family_type: response.family_type  // يجب أن يكون مطابقًا لاسم نوع العائلة
                });

                // تحديث الـ <select>
                updateFamilyTypeSelect();

                // إغلاق المودال
                $('#addFamilyTypeModal').modal('hide');
                $('.modal-backdrop').remove();

                // إعادة تعيين حقل الإدخال
                document.getElementById('newFamilyType').value = '';
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseText);
            }
        });
    } else {
        alert("الرجاء إدخال نوع العائلة");
         // إغلاق المودال
        $('#addFamilyTypeModal').modal('hide');
                $('.modal-backdrop').remove();
    }
});

document.getElementById('saveSupply').addEventListener('click', function() {
    let newSupply = document.getElementById('newSupply').value;

    if (newSupply !== "") {
        // استخدام AJAX لإرسال البيانات وحفظها
        $.ajax({
            url: '/supplies',  // قم بتعديل الرابط حسب الحاجة
            type: 'POST',
            data: {
                supply_name: newSupply,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // إضافة الاختيار الجديد إلى المصفوفة
                supplyOptions.push({
                    id: response.id,  // يجب أن يكون مطابقًا لاسم الـ ID الذي يعيده الخادم
                    supply_name: response.supply_name  // يجب أن يكون مطابقًا لاسم المواد
                });

                // تحديث الـ <select>
                updateSuppliesSelect();

                // إغلاق المودال
                $('#addSupplyModal').modal('hide');
                $('.modal-backdrop').remove();

                // إعادة تعيين حقل الإدخال
                document.getElementById('newSupply').value = '';
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseText);
            }
        });
    } else {
        alert("الرجاء إدخال المواد الأساسية");
        // إغلاق المودال
        $('#addSupplyModal').modal('hide');
                $('.modal-backdrop').remove();
    }
});

document.getElementById('saveOrganizationType').addEventListener('click', function() {
    let newOrganizationType = document.getElementById('newOrganizationType').value;

    if (newOrganizationType !== "") {
        // استخدام AJAX لإرسال البيانات وحفظها
        $.ajax({
            url: '/organization-type',  // قم بتعديل الرابط حسب الحاجة
            type: 'POST',
            data: {
                type_name: newOrganizationType,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // إضافة الاختيار الجديد إلى المصفوفة
                organizationTypeOptions.push({
                    id: response.id,  // يجب أن يكون مطابقًا لاسم الـ ID الذي يعيده الخادم
                    type_name: response.type_name  // يجب أن يكون مطابقًا لاسم النوع
                });

                // تحديث الـ <select>
                updateOrganizationTypeSelect();

                // إغلاق المودال
                $('#addOrganizationTypeModal').modal('hide');
                $('.modal-backdrop').remove();

                // إعادة تعيين حقل الإدخال
                document.getElementById('newOrganizationType').value = '';
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseText);
            }
        });
    } else {
        alert("الرجاء إدخال النوع");
        // إغلاق المودال
        $('#addOrganizationTypeModal').modal('hide');
                $('.modal-backdrop').remove();
    }
});
// تحويل الـ select إلى select2
$(document).ready(function() {
    $('#talents').select2();

    $('#talents').select2({
        placeholder: ' الموهبة',
        allowClear: true
    });

    document.getElementById('saveTalent').addEventListener('click', function() {
        let newTalent = document.getElementById('newTalent').value;

        if (newTalent !== "") {
            // استخدام AJAX لإرسال البيانات وحفظها
            $.ajax({
                url: '/talents',  // قم بتعديل الرابط حسب الحاجة
                type: 'POST',
                data: {
                    talent: newTalent,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // إضافة الموهبة الجديدة إلى المصفوفة
                    talentOptions.push({
                        id: response.id,  // يجب أن يكون مطابقًا لاسم الـ ID الذي يعيده الخادم
                        talent: response.talent  // يجب أن يكون مطابقًا لاسم الموهبة
                    });

                    // تحديث الـ <select> بعد إضافة الموهبة الجديدة
                    updateTalentSelect();

                    // إغلاق المودال
                    $('#addTalentModal').modal('hide');
                $('.modal-backdrop').remove();

                    // إعادة تعيين حقل الإدخال
                    document.getElementById('newTalent').value = '';
                },
                error: function(xhr) {
                    console.log('Error:', xhr.responseText);
                }
            });
        } else {
            alert("الرجاء إدخال الموهبة");
            $('#addTalentModal').modal('hide');
                $('.modal-backdrop').remove();
        }
    });
});


// إضافة حدث لحفظ المرحلة الدراسية الجديدة
document.getElementById('saveStudyLevel').addEventListener('click', function() {
    let newStudyLevel = document.getElementById('newStudyLevel').value;

    if (newStudyLevel !== "") {
        // استخدام AJAX لإرسال البيانات وحفظها
        $.ajax({
            url: '/study-levels',  // قم بتعديل الرابط حسب الحاجة
            type: 'POST',
            data: {
                level_name: newStudyLevel,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // إضافة المرحلة الدراسية الجديدة إلى المصفوفة
                studyLevelOptions.push({
                    id: response.id,  // يجب أن يكون مطابقًا لاسم الـ ID الذي يعيده الخادم
                    level_name: response.level_name  // يجب أن يكون مطابقًا لاسم المرحلة الدراسية
                });

                // تحديث الـ <select> بعد إضافة المرحلة الدراسية الجديدة
                updateStudyLevelSelect();

                // إغلاق المودال
                $('#addStudyLevelModal').modal('hide');
                $('.modal-backdrop').remove();

                // إعادة تعيين حقل الإدخال
                document.getElementById('newStudyLevel').value = '';
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseText);
            }
        });
    } else {
        alert("الرجاء إدخال المرحلة الدراسية");
        $('#addStudyLevelModal').modal('hide');
                $('.modal-backdrop').remove();
    }
});


document.getElementById('saveClassName').addEventListener('click', function() {
    let newClass = document.getElementById('newClass').value;

    if (newClass !== "") {
        // استخدام AJAX لإرسال البيانات وحفظها
        $.ajax({
            url: '/class_select',  // قم بتعديل الرابط حسب الحاجة
            type: 'POST',
            data: {
                class_select: newClass,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // إضافة الصف الجديد إلى المصفوفة
                class_select.push({
                    id: response.id,  // يجب أن يكون مطابقًا لاسم الـ ID الذي يعيده الخادم
                    class_select: response.class_select  // يجب أن يكون مطابقًا لاسم الصف
                });

                // تحديث الـ <select> بعد إضافة الصف الجديد
                updateClassSelect();

                // إغلاق المودال
                $('#addGradeModal').modal('hide');
                $('.modal-backdrop').remove();

                // إعادة تعيين حقل الإدخال
                document.getElementById('newClass').value = '';
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseText);
            }
        });
    } else {
        alert("الرجاء إدخال الصف");
        $('#addGradeModal').modal('hide');
                $('.modal-backdrop').remove();
    }
});


// إضافة حدث لحفظ مرض جديد
document.getElementById('saveDiseaseType').addEventListener('click', function() {
    let newDiseaseType = document.getElementById('newDiseaseType').value;

    if (newDiseaseType !== "") {
        // استخدام AJAX لإرسال البيانات وحفظها
        $.ajax({
            url: '/disease-types',  // قم بتعديل الرابط حسب الحاجة
            type: 'POST',
            data: {
                disease_name: newDiseaseType,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // إضافة المرض الجديد إلى المصفوفة
                diseaseTypeOptions.push({
                    id: response.id,  // يجب أن يكون مطابقًا لاسم الـ ID الذي يعيده الخادم
                    disease_name: response.disease_name  // يجب أن يكون مطابقًا لاسم المرض
                });

                // تحديث الـ <select> بعد إضافة المرض الجديد
                updateDiseaseTypeSelect();

                // إغلاق المودال
                $('#addDiseaseTypeModal').modal('hide');
                $('.modal-backdrop').remove();

                // إعادة تعيين حقل الإدخال
                document.getElementById('newDiseaseType').value = '';
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseText);
            }
        });
    } else {
        alert("الرجاء إدخال اسم المرض");
        $('#addDiseaseTypeModal').modal('hide');
                $('.modal-backdrop').remove();
    }
});


// إضافة حدث لحفظ درس جديد
document.getElementById('saveLesson').addEventListener('click', function() {
    let newLesson = document.getElementById('newLesson').value;

    if (newLesson !== "") {
        // استخدام AJAX لإرسال البيانات وحفظها
        $.ajax({
            url: '/lessons',  // قم بتعديل الرابط حسب الحاجة
            type: 'POST',
            data: {
                lesson_name: newLesson,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // إضافة الدرس الجديد إلى المصفوفة
                lessonOptions.push({
                    id: response.id,  // يجب أن يكون مطابقًا لاسم الـ ID الذي يعيده الخادم
                    lesson_name: response.lesson_name  // يجب أن يكون مطابقًا لاسم الدرس
                });

                // تحديث الـ <select> بعد إضافة الدرس الجديد
                updateLessonSelect();

                // إغلاق المودال
                $('#addLessonModal').modal('hide');
                $('.modal-backdrop').remove();

                // إعادة تعيين حقل الإدخال
                document.getElementById('newLesson').value = '';
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseText);
            }
        });
    } else {
        alert("الرجاء إدخال اسم الدرس");
        $('#addLessonModal').modal('hide');
                $('.modal-backdrop').remove();
    }
});


// إضافة حدث لحفظ سنة جديدة
document.getElementById('saveYear').addEventListener('click', function() {
    let newYear = document.getElementById('newYear').value;

    if (newYear !== "") {
        // استخدام AJAX لإرسال البيانات وحفظها
        $.ajax({
            url: '/years',  // قم بتعديل الرابط حسب الحاجة
            type: 'POST',
            data: {
                year: newYear,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // إضافة السنة الجديدة إلى المصفوفة
                yearOptions.push({
                    id: response.id,  // يجب أن يكون مطابقًا لاسم الـ ID الذي يعيده الخادم
                    year: response.year  // يجب أن يكون مطابقًا للسنة
                });

                // تحديث الـ <select> بعد إضافة السنة الجديدة
                updateYearSelect();

                // إغلاق المودال
                $('#addYearModal').modal('hide');
                $('.modal-backdrop').remove();

                // إعادة تعيين حقل الإدخال
                document.getElementById('newYear').value = '';
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseText);
            }
        });
    } else {
        alert("الرجاء إدخال السنة");
        $('#addYearModal').modal('hide');
                $('.modal-backdrop').remove();
    }
});

// دالة لحذف سنة
function deleteYear(yearId) {
    // استخدام AJAX لحذف السنة من قاعدة البيانات
    $.ajax({
        url: `/years/${yearId}`,  // تأكد من تعديل الرابط ليتوافق مع الـ route الخاص بالحذف
        type: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // إزالة السنة من المصفوفة
            yearOptions = yearOptions.filter(option => option.id !== yearId);

            // تحديث الـ <select> بعد حذف السنة
            updateYearSelect();
        },
        error: function(xhr) {
            console.log('Error:', xhr.responseText);
        }
    });
}

// إضافة حدث لحفظ فصل دراسي جديد
document.getElementById('saveSemester').addEventListener('click', function() {
    let newSemester = document.getElementById('newSemester').value;

    if (newSemester !== "") {
        // استخدام AJAX لإرسال البيانات وحفظها
        $.ajax({
            url: '/row-select',  // قم بتعديل الرابط حسب الحاجة
            type: 'POST',
            data: {
                row_name: newSemester,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // إضافة الفصل الدراسي الجديد إلى المصفوفة
                rowSelectOptions.push({
                    id: response.id,  // يجب أن يكون مطابقًا لاسم الـ ID الذي يعيده الخادم
                    row_name: response.row_name  // يجب أن يكون مطابقًا لاسم الفصل الدراسي
                });

                // تحديث الـ <select> بعد إضافة الفصل الدراسي الجديد
                updateSemesterSelect();

                // إغلاق المودال
                $('#addSemesterModal').modal('hide');
                $('.modal-backdrop').remove();

                // إعادة تعيين حقل الإدخال
                document.getElementById('newSemester').value = '';
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseText);
            }
        });
    } else {
        alert("الرجاء إدخال الفصل الدراسي");
        $('#addSemesterModal').modal('hide');
                $('.modal-backdrop').remove();
    }
});


// إضافة حدث لحفظ صف جديد
document.getElementById('saveGrade').addEventListener('click', function() {
    let newGrade = document.getElementById('newGrade').value;

    if (newGrade !== "") {
        // استخدام AJAX لإرسال البيانات وحفظها
        $.ajax({
            url: '/grades',  // قم بتعديل الرابط حسب الحاجة
            type: 'POST',
            data: {
                grade_name: newGrade,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // إضافة الصف الجديد إلى المصفوفة
                gradeOptions.push({
                    id: response.id,  // يجب أن يكون مطابقًا لاسم الـ ID الذي يعيده الخادم
                    grade_name: response.grade_name  // يجب أن يكون مطابقًا لاسم الصف
                });

                // تحديث الـ <select> بعد إضافة الصف الجديد
                updateGradeSelect();

                // إغلاق المودال
                $('#addDegreeModal').modal('hide');
                $('.modal-backdrop').remove();

                // إعادة تعيين حقل الإدخال
                document.getElementById('newGrade').value = '';
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseText);
            }
        });
    } else {
        alert("الرجاء إدخال اسم الصف");
        $('#addDegreeModal').modal('hide');
                $('.modal-backdrop').remove();
    }
});

// إضافة حدث لحفظ معلم جديد
document.getElementById('saveTeacher').addEventListener('click', function() {
    let newTeacher = document.getElementById('newTeacher').value;

    if (newTeacher !== "") {
        // استخدام AJAX لإرسال البيانات وحفظها
        $.ajax({
            url: '/teachers',  // قم بتعديل الرابط حسب الحاجة
            type: 'POST',
            data: {
                teacher_name: newTeacher,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // إضافة المعلم الجديد إلى المصفوفة
                teacherOptions.push({
                    id: response.id,  // يجب أن يكون مطابقًا لاسم الـ ID الذي يعيده الخادم
                    teacher_name: response.teacher_name  // يجب أن يكون مطابقًا لاسم المعلم
                });

                // تحديث الـ <select> بعد إضافة المعلم الجديد
                updateTeacherSelect();

                // إغلاق المودال
                $('#addTeacherModal').modal('hide');
                $('.modal-backdrop').remove();

                // إعادة تعيين حقل الإدخال
                document.getElementById('newTeacher').value = '';
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseText);
            }
        });
    } else {
        alert("الرجاء إدخال اسم المعلم");
        $('#addTeacherModal').modal('hide');
                $('.modal-backdrop').remove();
    }
});


// إضافة حدث لحفظ دورة جديدة
document.getElementById('saveDevelopmentCourse').addEventListener('click', function() {
    let newDevelopmentCourse = document.getElementById('newDevelopmentCourse').value;

    if (newDevelopmentCourse !== "") {
        // استخدام AJAX لإرسال البيانات وحفظها
        $.ajax({
            url: '/development-courses',  // قم بتعديل الرابط حسب الحاجة
            type: 'POST',
            data: {
                course_name: newDevelopmentCourse,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // إضافة الدورة الجديدة إلى المصفوفة
                developmentCoursesOptions.push({
                    id: response.id,  // يجب أن يكون مطابقًا لاسم الـ ID الذي يعيده الخادم
                    course_name: response.course_name  // يجب أن يكون مطابقًا لاسم الدورة
                });

                // تحديث الـ <select> بعد إضافة الدورة الجديدة
                updateDevelopmentCoursesSelect();

                // إغلاق المودال
                $('#addDevelopmentCourseModal').modal('hide');
                $('.modal-backdrop').remove();

                // إعادة تعيين حقل الإدخال
                document.getElementById('newDevelopmentCourse').value = '';
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseText);
            }
        });
    } else {
        alert("الرجاء إدخال اسم الدورة");
        $('#addDevelopmentCourseModal').modal('hide');
                $('.modal-backdrop').remove();
    }
});


document.getElementById('saveCity').addEventListener('click', function() {
    let newCityName = document.getElementById('newCityName').value;
    let province_id = document.getElementById('province_id_modal').value;

    if (newCityName !== "") {
        // استخدام AJAX لإرسال البيانات وحفظها
        $.ajax({
            url: '/cities',  // قم بتعديل الرابط حسب الحاجة
            type: 'POST',
            data: {
                name: newCityName,
                province_id: province_id,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {

                // إغلاق المودال
                $('#addCityModal').modal('hide');
                $('.modal-backdrop').remove();

                // إعادة تعيين حقل الإدخال
                document.getElementById('newCityName').value = '';
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseText);
            }
        });
    } else {
        alert("الرجاء إدخال اسم المدينة");
        $('#addCityModal').modal('hide');
                $('.modal-backdrop').remove();
    }
});


document.getElementById('saveProvince').addEventListener('click', function() {
    let newProvinceName = document.getElementById('newProvinceName').value;

    if (newProvinceName !== "") {
        // استخدام AJAX لإرسال البيانات وحفظها
        $.ajax({
            url: '/provinces',  // قم بتعديل الرابط حسب الحاجة
            type: 'POST',
            data: {
                name: newProvinceName,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // إضافة الاختيار الجديد إلى المصفوفة
                provinceOptions.push({
                    id: response.id,  // يجب أن يكون مطابقًا لاسم الـ ID الذي يعيده الخادم
                    name: response.name  // يجب أن يكون مطابقًا لاسم المحافظة
                });

                // تحديث الـ <select>
                updateProvinceSelect();

                // إغلاق المودال
                $('#addProvinceModal').modal('hide');
                $('.modal-backdrop').remove();

                // إعادة تعيين حقل الإدخال
                document.getElementById('newProvinceName').value = '';
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseText);
            }
        });
    } else {
        alert("الرجاء إدخال اسم المحافظة");
        $('#addProvinceModal').modal('hide');
                $('.modal-backdrop').remove();
    }
});


