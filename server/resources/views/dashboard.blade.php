<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }} - API Dashboard</title>
        <style>
            body {
                font-family: 'Arial', sans-serif;
                margin: 0;
                padding: 20px;
                background: #f5f5f5;
                color: #333;
            }
            .container {
                max-width: 1200px;
                margin: 0 auto;
                background: white;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                padding: 30px;
            }
            h1 {
                color: #2c3e50;
                text-align: center;
                margin-bottom: 30px;
            }
            h2 {
                color: #34495e;
                border-bottom: 2px solid #3498db;
                padding-bottom: 10px;
            }
            .section {
                margin-bottom: 30px;
            }
            .credentials {
                background: #ecf0f1;
                padding: 20px;
                border-radius: 5px;
                margin-bottom: 20px;
            }
            .credentials h3 {
                margin-top: 0;
                color: #27ae60;
            }
            .endpoint {
                background: #f8f9fa;
                border: 1px solid #dee2e6;
                border-radius: 4px;
                padding: 15px;
                margin-bottom: 15px;
            }
            .endpoint h4 {
                margin: 0 0 10px 0;
                color: #495057;
            }
            .method {
                display: inline-block;
                padding: 4px 8px;
                border-radius: 4px;
                font-weight: bold;
                font-size: 12px;
                margin-right: 10px;
            }
            .get { background: #28a745; color: white; }
            .post { background: #007bff; color: white; }
            .put { background: #ffc107; color: black; }
            .delete { background: #dc3545; color: white; }
            .url {
                font-family: monospace;
                background: #e9ecef;
                padding: 5px 10px;
                border-radius: 3px;
                display: inline-block;
            }
            .description {
                margin-top: 10px;
                font-size: 14px;
                color: #6c757d;
            }
            .filament-link {
                display: inline-block;
                background: #3498db;
                color: white;
                padding: 12px 25px;
                text-decoration: none;
                border-radius: 5px;
                margin: 20px 0;
                font-weight: bold;
            }
            .filament-link:hover {
                background: #2980b9;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>🕌 Quran School Management System</h1>
            
            <div class="section">
                <h2>📚 System Overview</h2>
                <p>This is a comprehensive Quran school management system with:</p>
                <ul>
                    <li><strong>Classes/Rooms:</strong> Teachers manage classes with students</li>
                    <li><strong>Teacher Accounts:</strong> Access to Filament dashboard and mobile app</li>
                    <li><strong>Student Accounts:</strong> Access to mobile app for assignments</li>
                    <li><strong>Assignments:</strong> Quran pages for reading, revision, or memorizing</li>
                    <li><strong>Progress Tracking:</strong> Complete history of assignments and completions</li>
                </ul>
            </div>

            <div class="section">
                <h2>🔐 Test Credentials</h2>
                
                <div class="credentials">
                    <h3>👨‍💼 Admin Account</h3>
                    <p><strong>Email:</strong> admin@hamidiapp.com</p>
                    <p><strong>Password:</strong> password</p>
                    <p><strong>Role:</strong> Administrator - Full system access</p>
                </div>

                <div class="credentials">
                    <h3>👨‍🏫 Teacher Accounts</h3>
                    <p><strong>Email:</strong> teacher1@hamidiapp.com, teacher2@hamidiapp.com, teacher3@hamidiapp.com</p>
                    <p><strong>Password:</strong> password</p>
                    <p><strong>Role:</strong> Teacher - Can manage students and assignments</p>
                </div>

                <div class="credentials">
                    <h3>👨‍🎓 Student Accounts</h3>
                    <p><strong>Email:</strong> student1@hamidiapp.com to student20@hamidiapp.com</p>
                    <p><strong>Password:</strong> password</p>
                    <p><strong>Role:</strong> Student - <strong>API access only</strong> (no Filament dashboard access)</p>
                </div>
            </div>

            <div class="section">
                <h2>🎛️ Admin Dashboard</h2>
                <a href="/admin" class="filament-link">Access Filament Admin Panel</a>
                <p>Use admin credentials to access the full admin dashboard with all resources.</p>
            </div>

            <div class="section">
                <h2>🔌 API Endpoints</h2>

                <h3>Authentication</h3>
                <div class="endpoint">
                    <h4><span class="method post">POST</span> <span class="url">/api/auth/login</span></h4>
                    <div class="description">Login with email and password to get API token</div>
                </div>

                <div class="endpoint">
                    <h4><span class="method post">POST</span> <span class="url">/api/auth/register</span></h4>
                    <div class="description">Register new teacher or student account</div>
                </div>

                <div class="endpoint">
                    <h4><span class="method get">GET</span> <span class="url">/api/auth/me</span></h4>
                    <div class="description">Get current authenticated user information</div>
                </div>

                <div class="endpoint">
                    <h4><span class="method post">POST</span> <span class="url">/api/auth/logout</span></h4>
                    <div class="description">Logout and invalidate API token</div>
                </div>

                <h3>Teacher Endpoints</h3>
                <div class="endpoint">
                    <h4><span class="method get">GET</span> <span class="url">/api/teacher/dashboard</span></h4>
                    <div class="description">Get teacher dashboard with stats, students, classes, and assignments</div>
                </div>

                <div class="endpoint">
                    <h4><span class="method get">GET</span> <span class="url">/api/teacher/students</span></h4>
                    <div class="description">Get all students assigned to the teacher</div>
                </div>

                <div class="endpoint">
                    <h4><span class="method get">GET</span> <span class="url">/api/teacher/students/{student}/progress</span></h4>
                    <div class="description">Get detailed progress for a specific student</div>
                </div>

                <div class="endpoint">
                    <h4><span class="method get">GET</span> <span class="url">/api/teacher/classes</span></h4>
                    <div class="description">Get all classes managed by the teacher</div>
                </div>

                <div class="endpoint">
                    <h4><span class="method get">GET</span> <span class="url">/api/teacher/classes/{class}/progress</span></h4>
                    <div class="description">Get class progress with all students' performance</div>
                </div>

                <h3>Student Endpoints</h3>
                <div class="endpoint">
                    <h4><span class="method get">GET</span> <span class="url">/api/student/dashboard</span></h4>
                    <div class="description">Get student dashboard with assignments and progress</div>
                </div>

                <div class="endpoint">
                    <h4><span class="method get">GET</span> <span class="url">/api/student/assignments</span></h4>
                    <div class="description">Get all assignments for the student (supports filtering by week, status)</div>
                </div>

                <div class="endpoint">
                    <h4><span class="method post">POST</span> <span class="url">/api/student/assignments/{assignment}/complete</span></h4>
                    <div class="description">Mark an assignment as completed</div>
                </div>

                <div class="endpoint">
                    <h4><span class="method put">PUT</span> <span class="url">/api/student/assignments/{assignment}/progress</span></h4>
                    <div class="description">Update assignment progress (0-100%)</div>
                </div>

                <div class="endpoint">
                    <h4><span class="method get">GET</span> <span class="url">/api/student/progress-history</span></h4>
                    <div class="description">Get complete history of assignment completions</div>
                </div>

                <h3>Assignment Management</h3>
                <div class="endpoint">
                    <h4><span class="method get">GET</span> <span class="url">/api/assignments</span></h4>
                    <div class="description">Get assignments (filtered by user role)</div>
                </div>

                <div class="endpoint">
                    <h4><span class="method post">POST</span> <span class="url">/api/assignments</span></h4>
                    <div class="description">Create new assignment (teachers only)</div>
                </div>

                <div class="endpoint">
                    <h4><span class="method get">GET</span> <span class="url">/api/assignments/{assignment}</span></h4>
                    <div class="description">Get detailed assignment information</div>
                </div>

                <div class="endpoint">
                    <h4><span class="method put">PUT</span> <span class="url">/api/assignments/{assignment}</span></h4>
                    <div class="description">Update assignment (teachers only)</div>
                </div>

                <div class="endpoint">
                    <h4><span class="method post">POST</span> <span class="url">/api/assignments/{assignment}/students/{student}/feedback</span></h4>
                    <div class="description">Add teacher feedback and rating to student's assignment</div>
                </div>

                <h3>Class Management</h3>
                <div class="endpoint">
                    <h4><span class="method get">GET</span> <span class="url">/api/classes</span></h4>
                    <div class="description">Get all classes</div>
                </div>

                <div class="endpoint">
                    <h4><span class="method post">POST</span> <span class="url">/api/classes</span></h4>
                    <div class="description">Create new class (teachers only)</div>
                </div>

                <div class="endpoint">
                    <h4><span class="method post">POST</span> <span class="url">/api/classes/{class}/students</span></h4>
                    <div class="description">Add student to class</div>
                </div>

                <div class="endpoint">
                    <h4><span class="method get">GET</span> <span class="url">/api/classes/{class}/weekly-progress</span></h4>
                    <div class="description">Get weekly progress for all students in class</div>
                </div>
            </div>

            <div class="section">
                <h2>📱 Usage Instructions</h2>
                <ol>
                    <li><strong>Authentication:</strong> Login via <code>/api/auth/login</code> to get a Bearer token</li>
                    <li><strong>Authorization:</strong> Include the token in the Authorization header: <code>Bearer {token}</code></li>
                    <li><strong>Teacher Flow:</strong> Use teacher dashboard → manage students → create assignments → track progress</li>
                    <li><strong>Student Flow:</strong> Use student dashboard → view assignments → update progress → mark as complete</li>
                    <li><strong>Admin Panel:</strong> Use Filament admin panel for comprehensive system management</li>
                </ol>
            </div>

            <div class="section">
                <h2>🎯 Key Features</h2>
                <ul>
                    <li>✅ Complete user authentication with roles (admin, teacher, student)</li>
                    <li>✅ Class/Room management with teacher assignments</li>
                    <li>✅ Flexible assignment system (individual or class-wide)</li>
                    <li>✅ Quran page-based assignments with types (reading, revision, memorizing)</li>
                    <li>✅ Progress tracking with percentages and completion status</li>
                    <li>✅ Teacher feedback and rating system</li>
                    <li>✅ Weekly assignment organization</li>
                    <li>✅ Complete assignment history for both teachers and students</li>
                    <li>✅ Filament admin dashboard for system management</li>
                    <li>✅ RESTful API for mobile app integration</li>
                </ul>
            </div>
        </div>
    </body>
</html>