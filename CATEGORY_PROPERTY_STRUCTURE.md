# Category and Property Database Structure

This document explains the comprehensive database structure for managing categories and their properties in a flexible, Arabic-focused system.

## Overview

The system is designed to handle:

-   **Hierarchical categories** with sector relationships
-   **Reusable properties** that can be attached to multiple categories
-   **Flexible property types** (text, number, select, checkbox, etc.)
-   **Arabic language support** with clean column names
-   **Property grouping** for better organization
-   **Customization per category** (labels, validation, options)
-   **Property values storage** for actual data

## Database Tables

### 1. Categories Table

Stores hierarchical categories organized by sectors.

```sql
categories
├── id (Primary Key)
├── name (Unique identifier)
├── sector_id (Foreign Key to sectors)
├── parent_id (Self-referencing for hierarchy)
├── title, description
├── display_order, icon, image, color
├── is_active, is_featured
├── meta_title, meta_description, meta_keywords
├── slug (SEO-friendly URL)
└── timestamps
```

**Key Features:**

-   Hierarchical structure (parent-child relationships)
-   Sector-based organization
-   Arabic titles and descriptions
-   SEO metadata support
-   Display ordering and visual customization

### 2. Properties Table

Stores reusable property definitions with various types and configurations.

```sql
properties
├── id (Primary Key)
├── name (Unique identifier)
├── title, description
├── type (text, textarea, number, select, multiselect, checkbox, radio, date, datetime, time, file, image, url, email, phone, color, range)
├── options (JSON array for select/multiselect)
├── default_value, unit, placeholder
├── validation_rules, min_length, max_length
├── min_value, max_value, min_selections, max_selections
├── is_required, is_searchable, is_filterable, is_sortable
├── is_unique, is_encrypted
├── group (Property group name)
├── conditional_logic (JSON for show/hide rules)
├── help_text
└── timestamps
```

**Property Types:**

-   **Text**: Single line text, textarea
-   **Numeric**: Number, decimal, range
-   **Selection**: Select, multiselect, radio, checkbox
-   **Date/Time**: Date, datetime, time
-   **File**: File upload, image upload
-   **Special**: URL, email, phone, color

### 3. Property Groups Table

Organizes properties into logical groups for better UI organization.

```sql
property_groups
├── id (Primary Key)
├── name (Unique identifier)
├── title, description
├── display_order, icon, color
├── is_collapsible, is_expanded_by_default
├── is_active
└── timestamps
```

**Example Groups:**

-   Basic Information
-   Technical Specifications
-   Dimensions & Measurements
-   Pricing & Availability

### 4. Category Properties Table (Pivot)

Links categories to properties with customization options.

```sql
category_properties
├── id (Primary Key)
├── category_id, property_id (Foreign Keys)
├── display_order, is_visible, is_editable, is_required
├── custom_label
├── custom_help_text
├── custom_validation_rules
├── custom_options (Override property options)
├── show_when, hide_when (Conditional display)
└── timestamps
```

**Customization Features:**

-   Override property labels per category
-   Custom validation rules per category
-   Custom options per category
-   Conditional display logic
-   Visibility and editability control

### 5. Property Values Table

Stores actual property values for items in categories.

```sql
property_values
├── id (Primary Key)
├── category_id, property_id (Foreign Keys)
├── item_id, item_type (Polymorphic relationship)
├── value (Text value)
├── json_value (Array/Object values)
├── numeric_value, boolean_value
├── date_value, datetime_value
├── unit, notes, source, is_verified
└── timestamps
```

**Value Storage:**

-   Different storage types based on property type
-   Polymorphic relationship for any item type
-   Verification status tracking
-   Source and notes for data quality

## Relationships

```
Sector (1) → (N) Categories
Category (1) → (N) Categories (self-referencing for hierarchy)
Category (N) ↔ (N) Properties (through category_properties)
Property (N) → (1) PropertyGroup
Property (1) → (N) PropertyValues
Category (1) → (N) PropertyValues
```

## Usage Examples

### 1. Creating a Category with Properties

```php
// Create a category
$category = Category::create([
    'name' => 'smartphones',
    'sector_id' => $sector->id,
    'title' => 'الهواتف الذكية',
    'slug' => 'smartphones'
]);

// Attach properties with customization
$category->properties()->attach($property->id, [
    'display_order' => 1,
    'is_required' => true,
    'custom_label' => 'Phone Model',
    'custom_help_text' => 'Enter the specific phone model'
]);
```

### 2. Getting Properties for a Category

```php
// Get all properties with customization
$properties = $category->properties()
    ->orderByPivot('display_order')
    ->get();

// Get properties by group
$basicInfo = Property::byGroup('basic_info')
    ->whereHas('categories', function($query) use ($category) {
        $query->where('category_id', $category->id);
    })
    ->get();
```

### 3. Storing Property Values

```php
// Store a property value
PropertyValue::create([
    'category_id' => $category->id,
    'property_id' => $property->id,
    'item_id' => $product->id,
    'item_type' => Product::class,
    'value' => 'iPhone 15 Pro'
]);

// Or use the setValue method for type-specific storage
$propertyValue = new PropertyValue([
    'category_id' => $category->id,
    'property_id' => $property->id,
    'item_id' => $product->id,
    'item_type' => Product::class
]);
$propertyValue->setValue('iPhone 15 Pro');
$propertyValue->save();
```

### 4. Retrieving Property Values

```php
// Get all values for a product
$values = PropertyValue::byItem($product->id, Product::class)
    ->with(['property', 'category'])
    ->get();

// Get formatted values
foreach ($values as $value) {
    echo $value->display_value; // Shows formatted value with labels
}
```

## Advanced Features

### 1. Conditional Display

Properties can be shown/hidden based on other property values:

```php
// Show warranty field only when brand is selected
$warrantyProperty->categories()->attach($category->id, [
    'show_when' => [
        'brand' => ['Apple', 'Samsung', 'Sony']
    ]
]);
```

### 2. Validation Rules

Properties include built-in validation with Laravel rules:

```php
$rules = $property->getValidationRules();
// Returns: ['required', 'numeric', 'min:0', 'max:100']
```

### 3. Arabic Language Support

All text fields support Arabic content:

```php
$property->title; // Returns Arabic title
$property->description; // Returns Arabic description
```

### 4. Property Grouping

Properties are organized into logical groups:

```php
$groups = PropertyGroup::active()->ordered()->get();
foreach ($groups as $group) {
    $properties = $group->getPropertiesForCategory($category);
    // Display properties in collapsible groups
}
```

## Migration Commands

```bash
# Run migrations
php artisan migrate

# Seed with sample data
php artisan db:seed --class=CategoryPropertySeeder

# Rollback if needed
php artisan migrate:rollback
```

## Benefits of This Structure

1. **Flexibility**: Properties can be reused across categories
2. **Customization**: Each category can customize property behavior
3. **Scalability**: Easy to add new properties and categories
4. **Arabic Focused**: Clean column names for Arabic content
5. **Type Safety**: Different storage types for different property types
6. **Validation**: Built-in validation rules and constraints
7. **Organization**: Logical grouping of related properties
8. **Performance**: Optimized with proper indexes and relationships

## Best Practices

1. **Property Naming**: Use descriptive, unique names for properties
2. **Grouping**: Organize properties into logical groups
3. **Validation**: Set appropriate validation rules for each property
4. **Indexing**: Ensure proper database indexes for performance
5. **Caching**: Cache frequently accessed property configurations
6. **Arabic Content**: Always provide proper Arabic text for user-facing content
7. **Documentation**: Document custom validation rules and logic

This structure provides a robust foundation for building flexible, scalable category-property systems that can handle complex business requirements while maintaining good performance and maintainability, with a focus on Arabic language support.
