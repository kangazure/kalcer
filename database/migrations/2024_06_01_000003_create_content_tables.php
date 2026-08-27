<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->string('name', 100);
            $table->string('slug', 120)->unique();
            $table->text('description')->nullable();
            $table->string('icon', 50)->nullable();
            $table->text('cover_image_url')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('seo_title', 160)->nullable();
            $table->string('seo_description', 320)->nullable();
            $table->timestampsTz();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('name', 60);
            $table->string('slug', 80)->unique();
            $table->integer('usage_count')->default(0);
            $table->timestampsTz();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->foreignId('author_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('title', 220);
            $table->string('slug', 250)->unique();
            $table->string('excerpt', 500)->nullable();
            $table->longText('content');
            $table->longText('content_html')->nullable();
            $table->text('cover_image_url')->nullable();
            $table->string('status', 20)->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_pinned')->default(false);
            $table->integer('reading_time_minutes')->default(1);
            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedBigInteger('like_count')->default(0);
            $table->unsignedBigInteger('comment_count')->default(0);
            $table->unsignedBigInteger('bookmark_count')->default(0);
            $table->unsignedBigInteger('share_count')->default(0);
            $table->string('seo_title', 160)->nullable();
            $table->string('seo_description', 320)->nullable();
            $table->text('og_image_url')->nullable();
            $table->text('canonical_url')->nullable();
            $table->timestampTz('published_at')->nullable();
            $table->timestampTz('scheduled_at')->nullable();
            $table->softDeletesTz('deleted_at');
            $table->timestampsTz();
            $table->index(['status', 'published_at']);
        });

        Schema::create('post_tag', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->foreignId('tag_id')->constrained('tags')->onDelete('cascade');
            $table->timestampTz('created_at')->useCurrent();
            $table->unique(['post_id', 'tag_id']);
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade');
            $table->string('guest_name', 100)->nullable();
            $table->string('guest_email', 191)->nullable();
            $table->text('content');
            $table->string('status', 20)->default('pending');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->integer('like_count')->default(0);
            $table->timestampsTz();
        });

        Schema::create('post_likes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('fingerprint', 64)->nullable();
            $table->timestampTz('created_at')->useCurrent();
        });

        Schema::create('post_bookmarks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestampTz('created_at')->useCurrent();
            $table->unique(['post_id', 'user_id']);
        });

        Schema::create('services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('name', 150);
            $table->string('slug', 170)->unique();
            $table->string('icon', 60)->nullable();
            $table->string('short_description', 300)->nullable();
            $table->longText('description')->nullable();
            $table->json('features')->nullable();
            $table->json('benefits')->nullable();
            $table->text('cover_image_url')->nullable();
            $table->text('icon_image_url')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured_home')->default(false);
            $table->string('seo_title', 160)->nullable();
            $table->string('seo_description', 320)->nullable();
            $table->timestampsTz();
        });

        Schema::create('packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->foreignId('service_id')->nullable()->constrained('services')->onDelete('set null');
            $table->string('category', 30)->default('home');
            $table->string('name', 120);
            $table->string('slug', 140)->unique();
            $table->integer('speed_mbps_download');
            $table->integer('speed_mbps_upload');
            $table->decimal('price', 12, 2);
            $table->decimal('price_promo', 12, 2)->nullable();
            $table->string('billing_cycle', 20)->default('monthly');
            $table->boolean('is_unlimited')->default(true);
            $table->integer('fup_gb')->nullable();
            $table->decimal('installation_fee', 12, 2)->default(0);
            $table->json('features')->nullable();
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestampsTz();
        });

        Schema::create('media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->foreignId('uploader_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('bucket', 50)->default('media');
            $table->text('storage_path');
            $table->text('public_url')->nullable();
            $table->string('file_name', 255);
            $table->string('original_name', 255);
            $table->string('mime_type', 120);
            $table->string('type', 20)->default('image');
            $table->unsignedBigInteger('size_bytes')->default(0);
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->integer('duration_seconds')->nullable();
            $table->string('alt_text', 255)->nullable();
            $table->text('caption')->nullable();
            $table->string('collection_name', 80)->default('default');
            $table->string('model_type', 100)->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->integer('sort_order')->default(0);
            $table->softDeletesTz('deleted_at');
            $table->timestampsTz();
        });

        Schema::create('gallery', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('title', 180);
            $table->string('slug', 200)->unique();
            $table->text('description')->nullable();
            $table->text('cover_image_url')->nullable();
            $table->string('category', 60)->nullable();
            $table->boolean('is_published')->default(true);
            $table->integer('sort_order')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestampsTz();
        });

        Schema::create('portfolio', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('title', 200);
            $table->string('slug', 220)->unique();
            $table->string('client_name', 150)->nullable();
            $table->string('category', 60)->nullable();
            $table->string('location', 150)->nullable();
            $table->string('summary', 500)->nullable();
            $table->longText('description')->nullable();
            $table->text('cover_image_url')->nullable();
            $table->string('result_metric_label', 100)->nullable();
            $table->string('result_metric_value', 50)->nullable();
            $table->smallInteger('project_year')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('seo_title', 160)->nullable();
            $table->string('seo_description', 320)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestampsTz();
        });

        Schema::create('team', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('name', 150);
            $table->string('position', 100);
            $table->string('department', 100)->nullable();
            $table->text('photo_url')->nullable();
            $table->text('bio')->nullable();
            $table->text('linkedin_url')->nullable();
            $table->string('email', 191)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_management')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestampsTz();
        });

        Schema::create('career', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('title', 180);
            $table->string('slug', 200)->unique();
            $table->string('department', 100)->nullable();
            $table->string('location', 150)->default('Lampung Timur');
            $table->string('job_type', 30)->default('full_time');
            $table->longText('description');
            $table->json('requirements')->nullable();
            $table->json('responsibilities')->nullable();
            $table->json('benefits')->nullable();
            $table->decimal('salary_min', 12, 2)->nullable();
            $table->decimal('salary_max', 12, 2)->nullable();
            $table->boolean('salary_is_negotiable')->default(true);
            $table->integer('vacancy_count')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestampTz('closes_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestampsTz();
        });

        Schema::create('job_application', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->foreignId('career_id')->constrained('career')->onDelete('cascade');
            $table->string('full_name', 150);
            $table->string('email', 191);
            $table->string('phone', 20);
            $table->text('cover_letter')->nullable();
            $table->foreignId('resume_media_id')->nullable()->constrained('media')->onDelete('set null');
            $table->text('portfolio_url')->nullable();
            $table->text('linkedin_url')->nullable();
            $table->decimal('expected_salary', 12, 2)->nullable();
            $table->string('status', 30)->default('submitted');
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('reviewer_notes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestampsTz();
        });

        Schema::create('contact', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('name', 150);
            $table->string('email', 191);
            $table->string('phone', 20)->nullable();
            $table->string('subject', 200)->nullable();
            $table->text('message');
            $table->string('source', 50)->default('contact_form');
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->string('status', 30)->default('new');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->timestampTz('handled_at')->nullable();
            $table->text('notes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestampsTz();
        });

        Schema::create('faq', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('category', 80)->default('Umum');
            $table->string('question', 300);
            $table->text('answer');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('view_count')->default(0);
            $table->timestampsTz();
        });

        Schema::create('testimonial', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('customer_name', 150);
            $table->string('customer_role', 150)->nullable();
            $table->text('customer_photo_url')->nullable();
            $table->foreignId('package_id')->nullable()->constrained('packages')->onDelete('set null');
            $table->smallInteger('rating')->default(5);
            $table->text('content');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestampsTz();
        });

        Schema::create('subscriber', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('email', 191)->unique();
            $table->string('name', 150)->nullable();
            $table->boolean('is_verified')->default(false);
            $table->string('verification_token', 100)->nullable();
            $table->string('unsubscribe_token', 100)->unique();
            $table->timestampTz('subscribed_at')->useCurrent();
            $table->timestampTz('unsubscribed_at')->nullable();
            $table->string('source', 50)->default('website');
        });

        Schema::create('banner', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('title', 180);
            $table->string('position', 30)->default('home_hero');
            $table->text('image_url');
            $table->text('image_url_mobile')->nullable();
            $table->text('link_url')->nullable();
            $table->string('link_target', 10)->default('_self');
            $table->string('alt_text', 255)->nullable();
            $table->timestampTz('starts_at')->nullable();
            $table->timestampTz('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->unsignedBigInteger('click_count')->default(0);
            $table->unsignedBigInteger('impression_count')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestampsTz();
        });

        Schema::create('slider', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('title', 180);
            $table->string('subtitle', 300)->nullable();
            $table->text('description')->nullable();
            $table->text('image_url');
            $table->text('video_url')->nullable();
            $table->string('cta_label', 60)->nullable();
            $table->text('cta_url')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestampsTz();
        });

        Schema::create('popup', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('title', 180);
            $table->text('content')->nullable();
            $table->text('image_url')->nullable();
            $table->text('link_url')->nullable();
            $table->string('link_label', 60)->nullable();
            $table->string('display_rule', 30)->default('once_per_session');
            $table->integer('show_delay_ms')->default(2000);
            $table->timestampTz('starts_at')->nullable();
            $table->timestampTz('ends_at')->nullable();
            $table->boolean('is_active')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestampsTz();
        });

        Schema::create('announcement', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('title', 200);
            $table->text('content');
            $table->string('severity', 20)->default('info');
            $table->boolean('is_active')->default(true);
            $table->timestampTz('starts_at')->useCurrent();
            $table->timestampTz('ends_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestampsTz();
        });

        Schema::create('notification', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('channel', 30)->default('database');
            $table->string('type', 80);
            $table->string('title', 200);
            $table->text('body')->nullable();
            $table->text('action_url')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestampTz('read_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestampTz('created_at')->useCurrent();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('group_name', 60)->default('general');
            $table->string('key', 100);
            $table->json('value')->nullable();
            $table->string('label', 150)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false);
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestampsTz();
            $table->unique(['group_name', 'key']);
        });

        Schema::create('visitor', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('session_id', 64);
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('referrer')->nullable();
            $table->text('landing_page')->nullable();
            $table->string('country', 80)->nullable();
            $table->string('city', 80)->nullable();
            $table->string('device_type', 20)->nullable();
            $table->string('browser', 60)->nullable();
            $table->string('os', 60)->nullable();
            $table->timestampTz('visited_at')->useCurrent();
            $table->index('visited_at');
        });

        Schema::create('analytics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('metric_date');
            $table->string('page_path', 255)->nullable();
            $table->unsignedBigInteger('page_views')->default(0);
            $table->unsignedBigInteger('unique_visitors')->default(0);
            $table->integer('avg_duration_seconds')->default(0);
            $table->decimal('bounce_rate', 5, 2)->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->unique(['metric_date', 'page_path']);
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('log_name', 60)->default('default');
            $table->string('description', 300);
            $table->string('subject_type', 100)->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('event', 30);
            $table->json('properties')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->index(['created_at']);
        });

        Schema::create('network_monitor', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('node_name', 150);
            $table->string('node_type', 30)->default('pop');
            $table->string('ip_address', 45)->nullable();
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->string('status', 20)->default('unknown');
            $table->integer('bandwidth_capacity_mbps')->nullable();
            $table->decimal('bandwidth_usage_mbps', 10, 2)->nullable();
            $table->decimal('latency_ms', 8, 2)->nullable();
            $table->decimal('packet_loss_percent', 5, 2)->nullable();
            $table->decimal('uptime_percent', 5, 2)->nullable();
            $table->timestampTz('last_checked_at')->nullable();
            $table->timestampTz('last_down_at')->nullable();
            $table->foreignId('parent_node_id')->nullable()->constrained('network_monitor')->onDelete('set null');
            $table->timestampsTz();
        });

        Schema::create('network_monitor_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('node_id')->constrained('network_monitor')->onDelete('cascade');
            $table->decimal('bandwidth_usage_mbps', 10, 2)->nullable();
            $table->decimal('latency_ms', 8, 2)->nullable();
            $table->decimal('packet_loss_percent', 5, 2)->nullable();
            $table->string('status', 20);
            $table->timestampTz('recorded_at')->useCurrent();
            $table->index(['node_id', 'recorded_at']);
        });

        Schema::create('coverage_area', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('region_name', 150);
            $table->string('district', 100)->nullable();
            $table->string('regency', 100)->default('Lampung Timur');
            $table->string('province', 100)->default('Lampung');
            $table->decimal('center_latitude', 10, 6);
            $table->decimal('center_longitude', 10, 6);
            $table->integer('radius_meters')->default(3000);
            $table->json('polygon_geojson')->nullable();
            $table->string('coverage_status', 20)->default('available');
            $table->foreignId('pop_id')->nullable()->constrained('network_monitor')->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->timestampsTz();
        });

        Schema::create('maintenance', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('title', 200);
            $table->text('description');
            $table->json('affected_areas')->nullable();
            $table->json('affected_node_ids')->nullable();
            $table->string('status', 30)->default('scheduled');
            $table->timestampTz('scheduled_start');
            $table->timestampTz('scheduled_end');
            $table->timestampTz('actual_start')->nullable();
            $table->timestampTz('actual_end')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestampsTz();
        });

        Schema::create('trouble_report', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('reporter_name', 150)->nullable();
            $table->string('reporter_phone', 20)->nullable();
            $table->string('reporter_email', 191)->nullable();
            $table->string('customer_id_number', 50)->nullable();
            $table->foreignId('node_id')->nullable()->constrained('network_monitor')->onDelete('set null');
            $table->string('region_name', 150)->nullable();
            $table->string('title', 200);
            $table->text('description');
            $table->string('severity', 20)->default('medium');
            $table->string('status', 20)->default('open');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->text('resolution_notes')->nullable();
            $table->timestampTz('reported_at')->useCurrent();
            $table->timestampTz('resolved_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        $tables = [
            'trouble_report', 'maintenance', 'coverage_area', 'network_monitor_history', 'network_monitor',
            'activity_logs', 'analytics', 'visitor', 'settings', 'notification', 'announcement', 'popup',
            'slider', 'banner', 'subscriber', 'testimonial', 'faq', 'contact', 'job_application', 'career',
            'team', 'portfolio', 'gallery', 'media', 'packages', 'services', 'post_bookmarks', 'post_likes',
            'comments', 'post_tag', 'posts', 'tags', 'categories',
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }
};
