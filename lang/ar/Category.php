<?php

return [
    // Tab Names
    'tabs' => [
        'category_management' => 'إدارة الفئات',
        'basic_information' => 'المعلومات الأساسية',
        'properties' => 'الخصائص',
    ],

    // Section Names
    'sections' => [
        'basic_information' => 'المعلومات الأساسية',
        'display_organization' => 'العرض والتنظيم',
        'status_seo' => 'الحالة وتحسين محركات البحث',
        'property_management' => 'إدارة الخصائص',
        'quick_property_assignment' => 'التعيين السريع للخصائص',
    ],

    // Form Fields
    'fields' => [
        'name' => 'المعرف الفريد',
        'title' => 'العنوان',
        'description' => 'الوصف',
        'sector_id' => 'القطاع',
        'parent_id' => 'الفئة الأب',
        'display_order' => 'ترتيب العرض',
        'icon' => 'الأيقونة',
        'image' => 'الصورة',
        'color' => 'اللون',
        'is_active' => 'نشط',
        'is_featured' => 'مميز',
        'slug' => 'رابط SEO',
        'meta_title' => 'عنوان Meta',
        'meta_description' => 'وصف Meta',
        'meta_keywords' => 'كلمات مفتاحية Meta',
    ],

    // Property Fields
    'properties' => [
        'property' => 'الخاصية',
        'property_group' => 'مجموعة الخصائص',
        'custom_label' => 'التسمية المخصصة',
        'custom_help_text' => 'نص المساعدة المخصص',
        'custom_validation_rules' => 'قواعد التحقق المخصصة',
        'display_order' => 'ترتيب العرض',
        'is_visible' => 'مرئي',
        'is_editable' => 'قابل للتعديل',
        'is_required' => 'مطلوب',
        'custom_options' => 'الخيارات المخصصة',
        'show_when' => 'إظهار عند',
        'hide_when' => 'إخفاء عند',
    ],

    // Table Columns
    'columns' => [
        'name' => 'المعرف',
        'title' => 'العنوان',
        'sector' => 'القطاع',
        'parent' => 'الفئة الأب',
        'subcategories' => 'الفئات الفرعية',
        'properties' => 'الخصائص',
        'order' => 'الترتيب',
        'active' => 'نشط',
        'featured' => 'مميز',
        'created_at' => 'تاريخ الإنشاء',
    ],

    // Actions
    'actions' => [
        'quick_assign' => 'تعيين سريع',
        'manage_properties' => 'إدارة الخصائص',
        'add_property' => 'إضافة خاصية',
        'edit' => 'تعديل',
        'view' => 'عرض',
        'delete' => 'حذف',
    ],

    // Filters
    'filters' => [
        'sector' => 'تصفية حسب القطاع',
        'parent_category' => 'تصفية حسب الفئة الأب',
        'active_status' => 'حالة النشاط',
        'featured_status' => 'حالة التمييز',
        'has_children' => 'لها فئات فرعية',
        'root_categories' => 'الفئات الجذرية',
    ],

    // Helper Text
    'helpers' => [
        'name' => 'معرف فريد للفئة (مثال: إلكترونيات، هواتف)',
        'display_order' => 'الأرقام الأقل تظهر أولاً',
        'color' => 'لون لعناصر الواجهة',
        'is_active' => 'إظهار هذه الفئة للمستخدمين',
        'is_featured' => 'إبراز هذه الفئة',
        'slug' => 'نسخة صديقة للرابط من العنوان',
        'meta_title' => 'عنوان meta لتحسين محركات البحث',
        'meta_description' => 'وصف meta لتحسين محركات البحث',
        'meta_keywords' => 'كلمات مفتاحية meta (مفصولة بفواصل)',
        'custom_label' => 'تجاوز التسمية الافتراضية للخاصية لهذه الفئة',
        'custom_help_text' => 'تجاوز نص المساعدة الافتراضي لهذه الفئة',
        'custom_validation_rules' => 'تجاوز قواعد التحقق الافتراضية لهذه الفئة',
        'display_order_group' => 'الترتيب داخل المجموعة',
        'is_visible' => 'إظهار هذه الخاصية للمستخدمين',
        'is_editable' => 'السماح للمستخدمين بتعديل هذه الخاصية',
        'is_required' => 'جعل هذه الخاصية إلزامية',
        'custom_options' => 'تجاوز خيارات الخاصية لهذه الفئة (تنسيق JSON)',
        'show_when' => 'منطق شرطي لإظهار هذه الخاصية (تنسيق JSON)',
        'hide_when' => 'منطق شرطي لإخفاء هذه الخاصية (تنسيق JSON)',
        'quick_properties' => 'يظهر فقط الخصائص غير المرفقة بالفئة',
        'default_group' => 'سيتم تعيين الخصائص لهذه المجموعة افتراضياً',
    ],

    // Placeholders
    'placeholders' => [
        'title' => 'أدخل العنوان باللغة العربية',
        'description' => 'أدخل الوصف باللغة العربية',
        'parent_category' => 'اختر الفئة الأب (اختياري)',
        'icon' => 'heroicon-o-device-phone-mobile',
        'property_group' => 'اختر المجموعة (اختياري)',
        'properties_add' => 'اختر الخصائص للإضافة',
        'default_group' => 'اختر المجموعة الافتراضية',
        'custom_options' => '{"option1": "value1", "option2": "value2"}',
        'show_when' => '{"property": "value"}',
        'hide_when' => '{"property": "value"}',
    ],

    // Messages
    'messages' => [
        'properties_assigned' => 'تم تعيين الخصائص بنجاح',
        'properties_updated' => 'تم تحديث الخصائص بنجاح',
        'category_created' => 'تم إنشاء الفئة بنجاح',
        'category_updated' => 'تم تحديث الفئة بنجاح',
        'category_deleted' => 'تم حذف الفئة بنجاح',
    ],

    // Descriptions
    'descriptions' => [
        'property_management' => 'إرفاق الخصائص بهذه الفئة وتنظيمها في مجموعات',
        'quick_assignment' => 'إرفاق عدة خصائص بهذه الفئة بسرعة',
    ],

    // Labels
    'labels' => [
        'category_properties' => 'خصائص الفئة',
        'select_properties' => 'اختر الخصائص',
        'default_property_group' => 'المجموعة الافتراضية',
        'make_required' => 'جعل مطلوب',
        'make_visible' => 'جعل مرئي',
        'new_property' => 'خاصية جديدة',
    ],
];
