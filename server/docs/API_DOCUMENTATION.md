# 📱 Quran School Mobile App API Documentation

## 🏗️ **Architecture Overview**

The Quran School Management System provides a RESTful API designed for mobile applications. The system supports dual authentication for two distinct user types:

-   **Students**: Direct authentication for mobile app access only
-   **Teachers/Admins**: Authentication for both mobile app and web dashboard access

## 🔐 **Authentication Flow**

### Base URL

```
http://your-server.com/api
```

### 🔒 **Account Creation**

**Important Note:** User registration is **NOT** available through the API. All accounts (students, teachers, and admins) must be created through the **Filament Admin Dashboard**. The mobile app only provides login functionality using phone numbers and 4-digit PINs.

### Authentication Headers

All protected endpoints require:

```
Authorization: Bearer {token}
Content-Type: application/json
```

---

## 📋 **API Endpoints Reference**

### 🔑 **Authentication Endpoints**

#### 1. **Login (Universal)**

**Endpoint:** `POST /auth/login`

**Purpose:** Authenticate both students and teachers/admins using phone number and 4-digit PIN

**Request:**

```json
{
    "phone": "+966501234567",
    "pin": "1234"
}
```

**Response (Student):**

```json
{
    "user": {
        "id": 1,
        "name": "Student 1",
        "email": "student1@hamidiapp.com",
        "phone": "+966507654321",
        "date_of_birth": "2005-01-15",
        "teacher_id": 1,
        "class_id": 1,
        "student_id": "S0001",
        "guardian_name": "Parent Name",
        "guardian_phone": "+966507654322",
        "is_active": true,
        "teacher": {
            "id": 1,
            "user": {
                "id": 2,
                "name": "Teacher 1",
                "email": "teacher1@hamidiapp.com"
            }
        },
        "class": {
            "id": 1,
            "name": "Class A",
            "description": "Beginner Quran Class"
        }
    },
    "user_type": "student",
    "role": "student",
    "token": "1|abc123...",
    "token_type": "Bearer"
}
```

**Response (Teacher):**

```json
{
    "user": {
        "id": 2,
        "name": "Teacher 1",
        "email": "teacher1@hamidiapp.com",
        "role": "teacher",
        "phone": "+966501234561",
        "teacher": {
            "id": 1,
            "employee_id": "T0001",
            "bio": "Experienced Quran teacher",
            "specialization": "Tajweed"
        }
    },
    "user_type": "user",
    "role": "teacher",
    "token": "2|def456...",
    "token_type": "Bearer"
}
```

#### 2. **Get Current User**

**Endpoint:** `GET /auth/me`

**Purpose:** Get current authenticated user/student information

**Headers:** `Authorization: Bearer {token}`

**Response:** Same format as login response

#### 3. **Logout**

**Endpoint:** `POST /auth/logout`

**Purpose:** Invalidate current authentication token

**Headers:** `Authorization: Bearer {token}`

**Response:**

```json
{
    "message": "Logged out successfully"
}
```

**Error Responses:**

**Invalid Credentials (422):**

```json
{
    "message": "The provided credentials are incorrect.",
    "errors": {
        "phone": ["The provided credentials are incorrect."]
    }
}
```

**Validation Error (422):**

```json
{
    "message": "The pin field must be 4 characters.",
    "errors": {
        "pin": [
            "The pin field must be 4 characters.",
            "The pin field format is invalid."
        ]
    }
}
```

**Unauthorized (401):**

```json
{
    "message": "Unauthenticated."
}
```

---

### 👨‍🎓 **Student Endpoints**

#### 1. **Student Dashboard**

**Endpoint:** `GET /student/dashboard`

**Purpose:** Get student's main dashboard data with stats and recent activity

**Headers:** `Authorization: Bearer {student_token}`

**Query Parameters:**

-   `week` (optional): Specific week number (1-53)

**Response:**

```json
{
    "student": {
        "id": 1,
        "name": "Student 1",
        "email": "student1@hamidiapp.com",
        "phone": "+966507654321",
        "student_id": "S0001",
        "date_of_birth": "2005-01-01",
        "guardian_name": "Guardian of Student 1",
        "guardian_phone": "+966509876541",
        "teacher": {
            "id": 1,
            "user_id": 2,
            "employee_id": "T0001",
            "specialization": "Tajweed",
            "user": {
                "id": 2,
                "name": "Teacher 1",
                "email": "teacher1@hamidiapp.com",
                "phone": "+966501234561"
            }
        },
        "class": {
            "id": 1,
            "name": "Class A",
            "description": "Beginner level Quran class focused on Tajweed",
            "capacity": 15,
            "room_number": "101"
        }
    },
    "stats": {
        "total_assignments": 12,
        "completed_assignments": 8,
        "pending_assignments": 4,
        "completion_rate": 67,
        "current_week": 47,
        "weekly_progress": {
            "pages_completed": 15,
            "pages_target": 20,
            "progress_percentage": 75
        }
    },
    "recent_assignments": [
        {
            "id": 1,
            "title": "Reading Assignment - Week 47",
            "type": "reading",
            "pages": [1, 2, 3],
            "due_date": "2025-11-22",
            "status": "pending",
            "progress": 60,
            "is_overdue": false
        },
        {
            "id": 2,
            "title": "Memorization Assignment - Week 47",
            "type": "memorizing",
            "pages": [4, 5],
            "due_date": "2025-11-22",
            "status": "completed",
            "progress": 100,
            "completed_at": "2025-11-20T10:30:00Z"
        }
    ],
    "teacher_feedback": [
        {
            "assignment_id": 2,
            "feedback": "Excellent memorization! Keep up the good work.",
            "rating": 5,
            "created_at": "2025-11-20T14:30:00Z"
        }
    ]
}
```

#### 2. **Get Assignments**

**Endpoint:** `GET /student/assignments`

**Purpose:** Get student's assignments with optional filtering

**Headers:** `Authorization: Bearer {student_token}`

**Query Parameters:**

-   `week` (optional): Filter by week number
-   `status` (optional): `completed`, `pending`, `overdue`

**Response:**

```json
{
    "assignments": [
        {
            "id": 1,
            "title": "Reading Assignment - Week 47",
            "description": "Weekly reading assignment for class A",
            "type": "reading",
            "pages": [1, 2, 3, 4, 5],
            "assigned_date": "2025-11-16",
            "due_date": "2025-11-22",
            "week_number": 47,
            "status": "pending",
            "progress": 60,
            "is_overdue": false,
            "teacher": {
                "id": 1,
                "name": "Teacher 1",
                "employee_id": "T0001"
            },
            "completion": {
                "id": 15,
                "completed_pages": [1, 2, 3],
                "notes": "Making good progress with reading",
                "last_updated": "2025-11-20T10:30:00Z",
                "teacher_feedback": null
            }
        },
        {
            "id": 2,
            "title": "Memorization Assignment - Week 47",
            "description": "Weekly memorization assignment for class A",
            "type": "memorizing",
            "pages": [10, 11],
            "assigned_date": "2025-11-16",
            "due_date": "2025-11-22",
            "week_number": 47,
            "status": "completed",
            "progress": 100,
            "is_overdue": false,
            "completion": {
                "id": 16,
                "completed_pages": [10, 11],
                "notes": "Successfully memorized both pages",
                "completed_at": "2025-11-21T16:45:00Z",
                "teacher_feedback": {
                    "feedback": "Excellent work! Perfect memorization.",
                    "rating": 5,
                    "created_at": "2025-11-21T18:00:00Z"
                }
            }
        }
    ],
    "meta": {
        "total": 2,
        "current_week": 47,
        "filter_status": "all",
        "has_more": false
    }
}
```

#### 3. **Mark Assignment Complete**

**Endpoint:** `POST /student/assignments/{assignment_id}/complete`

**Purpose:** Mark an assignment as completed

**Headers:** `Authorization: Bearer {student_token}`

**Request:**

```json
{
    "notes": "Completed successfully!"
}
```

**Response:**

```json
{
    "message": "Assignment marked as completed successfully",
    "completion": {
        "id": 15,
        "assignment_id": 1,
        "student_id": 1,
        "status": "completed",
        "progress": 100,
        "completed_at": "2025-11-20T15:45:32Z",
        "notes": "Completed successfully! Memorized all verses.",
        "created_at": "2025-11-16T08:00:00Z",
        "updated_at": "2025-11-20T15:45:32Z",
        "assignment": {
            "id": 1,
            "title": "Reading Assignment - Week 47",
            "description": "Weekly reading assignment for class A",
            "type": "reading",
            "pages": [1, 2, 3, 4, 5],
            "due_date": "2025-11-22",
            "week_number": 47,
            "teacher": {
                "id": 1,
                "name": "Teacher 1",
                "employee_id": "T0001"
            }
        }
    }
}
```

#### 4. **Update Assignment Progress**

**Endpoint:** `PUT /student/assignments/{assignment_id}/progress`

**Purpose:** Update progress percentage for an assignment

**Headers:** `Authorization: Bearer {student_token}`

**Request:**

```json
{
    "progress_percentage": 75,
    "notes": "Almost finished, need more practice"
}
```

**Response:**

```json
{
    "message": "Progress updated successfully",
    "completion": {
        "id": 15,
        "assignment_id": 1,
        "student_id": 1,
        "status": "in_progress",
        "progress": 75,
        "notes": "Almost finished, need more practice",
        "last_updated": "2025-11-20T14:30:00Z",
        "assignment": {
            "id": 1,
            "title": "Reading Assignment - Week 47",
            "type": "reading",
            "pages": [1, 2, 3, 4, 5],
            "due_date": "2025-11-22"
        }
    }
}
```

#### 5. **Get Progress History**

**Endpoint:** `GET /student/progress-history`

**Purpose:** Get complete history of student's assignment progress

**Headers:** `Authorization: Bearer {student_token}`

**Response:**

```json
[
    {
        "id": 1,
        "assignment_id": 1,
        "progress_percentage": 100,
        "is_completed": true,
        "teacher_feedback": "Excellent work!",
        "rating": 5,
        "created_at": "2025-11-16T10:00:00Z",
        "assignment": {
            "id": 1,
            "title": "Surah Al-Fatiha Memorization",
            "type": "memorizing"
        }
    }
]
```

---

### 👨‍🏫 **Teacher Endpoints**

#### 1. **Teacher Dashboard**

**Endpoint:** `GET /teacher/dashboard`

**Purpose:** Get teacher's main dashboard with overview of students and classes

**Headers:** `Authorization: Bearer {teacher_token}`

**Query Parameters:**

-   `week` (optional): Specific week number

**Response:**

```json
{
    "teacher": {
        "id": 1,
        "user_id": 2,
        "employee_id": "T0001",
        "bio": "Experienced Quran teacher with 6 years of teaching experience.",
        "specialization": "Tajweed",
        "hire_date": "2023-11-16",
        "is_active": true,
        "user": {
            "id": 2,
            "name": "Teacher 1",
            "email": "teacher1@hamidiapp.com",
            "phone": "+966501234561",
            "role": "teacher"
        }
    },
    "stats": {
        "total_students": 7,
        "active_students": 7,
        "total_classes": 1,
        "weekly_assignments": 3,
        "completion_rate": 68.5,
        "current_week": 47
    },
    "recent_students": [
        {
            "id": 1,
            "name": "Student 1",
            "student_id": "S0001",
            "class_name": "Class A",
            "completion_rate": 75,
            "last_activity": "2025-11-20T16:30:00Z"
        },
        {
            "id": 2,
            "name": "Student 2",
            "student_id": "S0002",
            "class_name": "Class A",
            "completion_rate": 60,
            "last_activity": "2025-11-19T14:15:00Z"
        }
    ],
    "active_classes": [
        {
            "id": 1,
            "name": "Class A",
            "description": "Beginner level Quran class focused on Tajweed",
            "capacity": 15,
            "current_students": 7,
            "room_number": "101",
            "schedule": {
                "sunday": ["09:00", "11:00"],
                "tuesday": ["09:00", "11:00"],
                "thursday": ["09:00", "11:00"]
            }
        }
    ],
    "recent_assignments": [
        {
            "id": 1,
            "title": "Reading Assignment - Week 47",
            "type": "reading",
            "class_name": "Class A",
            "due_date": "2025-11-22",
            "completed_count": 3,
            "total_students": 7,
            "completion_rate": 43
        }
    ]
}
```

#### 2. **Get Teacher's Students**

**Endpoint:** `GET /teacher/students`

**Purpose:** Get all students assigned to the teacher with progress information

**Headers:** `Authorization: Bearer {teacher_token}`

**Response:**

```json
{
    "students": [
        {
            "id": 1,
            "name": "Student 1",
            "email": "student1@hamidiapp.com",
            "phone": "+966507654321",
            "student_id": "S0001",
            "date_of_birth": "2005-01-01",
            "guardian_name": "Guardian of Student 1",
            "guardian_phone": "+966509876541",
            "is_active": true,
            "class": {
                "id": 1,
                "name": "Class A",
                "room_number": "101"
            },
            "stats": {
                "total_assignments": 12,
                "completed_assignments": 9,
                "completion_rate": 75,
                "average_progress": 82.5,
                "last_activity": "2025-11-20T16:30:00Z"
            },
            "recent_assignments": [
                {
                    "id": 1,
                    "title": "Reading Assignment - Week 47",
                    "type": "reading",
                    "status": "pending",
                    "progress": 60,
                    "due_date": "2025-11-22"
                },
                {
                    "id": 2,
                    "title": "Memorization Assignment - Week 47",
                    "type": "memorizing",
                    "status": "completed",
                    "progress": 100,
                    "completed_at": "2025-11-20T10:30:00Z"
                }
            ]
        },
        {
            "id": 2,
            "name": "Student 2",
            "email": "student2@hamidiapp.com",
            "phone": "+966507654322",
            "student_id": "S0002",
            "stats": {
                "completion_rate": 65,
                "total_assignments": 12,
                "completed_assignments": 8
            }
        }
    ],
    "meta": {
        "total": 7,
        "average_completion_rate": 68.5,
        "active_students": 7
    }
}
```

#### 3. **Get Student Progress**

**Endpoint:** `GET /teacher/students/{student_id}/progress`

**Purpose:** Get detailed progress for a specific student

**Headers:** `Authorization: Bearer {teacher_token}`

**Query Parameters:**

-   `week` (optional): Specific week number

**Response:**

```json
{
  "student": { ... },
  "completion_rate": 75.5,
  "weekly_assignments": [ ... ],
  "progress_history": [ ... ]
}
```

#### 4. **Get Teacher's Classes**

**Endpoint:** `GET /teacher/classes`

**Purpose:** Get all classes managed by the teacher

**Headers:** `Authorization: Bearer {teacher_token}`

**Response:**

```json
[
    {
        "class": {
            "id": 1,
            "name": "Class A",
            "description": "Beginner Quran Class",
            "capacity": 15,
            "room_number": "101"
        },
        "student_count": 12,
        "has_capacity": true
    }
]
```

#### 5. **Get Class Progress**

**Endpoint:** `GET /teacher/classes/{class_id}/progress`

**Purpose:** Get progress overview for a specific class

**Headers:** `Authorization: Bearer {teacher_token}`

**Query Parameters:**

-   `week` (optional): Specific week number

**Response:**

```json
{
  "class": { ... },
  "progress": {
    "total_students": 12,
    "average_completion_rate": 78.5,
    "completed_assignments": 45,
    "pending_assignments": 15
  },
  "weekly_assignments": [ ... ]
}
```

---

### 📚 **Assignment Management Endpoints**

#### 1. **Get Assignments**

**Endpoint:** `GET /assignments`

**Purpose:** Get assignments (filtered by user type automatically)

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**

-   `week_number` (optional): Filter by week
-   `type` (optional): `reading`, `revision`, `memorizing`

**Response:** Array of assignment objects

#### 2. **Create Assignment**

**Endpoint:** `POST /assignments`

**Purpose:** Create new assignment (teachers only)

**Headers:** `Authorization: Bearer {teacher_token}`

**Request:**

```json
{
    "title": "Weekly Quran Reading",
    "description": "Read pages 1-5 with proper tajweed",
    "type": "reading",
    "pages": [1, 2, 3, 4, 5],
    "assigned_date": "2025-11-16",
    "due_date": "2025-11-23",
    "class_id": 1,
    "week_number": 47
}
```

**Note:** Use either `student_id` for individual assignment or `class_id` for class assignment

#### 3. **Get Assignment Details**

**Endpoint:** `GET /assignments/{assignment_id}`

**Purpose:** Get detailed assignment information

**Response:**

```json
{
  "assignment": { ... },
  "completion_rate": 65.0,
  "target_students": [ ... ],
  "is_overdue": false
}
```

#### 4. **Add Teacher Feedback**

**Endpoint:** `POST /assignments/{assignment_id}/students/{student_id}/feedback`

**Purpose:** Add teacher feedback to student's assignment completion

**Headers:** `Authorization: Bearer {teacher_token}`

**Request:**

```json
{
    "feedback": "Excellent memorization! Keep practicing the pronunciation.",
    "rating": 5
}
```

---

### 🏫 **Class Management Endpoints**

#### 1. **Get Classes**

**Endpoint:** `GET /classes`

**Purpose:** Get list of classes

**Response:** Array of class objects

#### 2. **Add Student to Class**

**Endpoint:** `POST /classes/{class_id}/students`

**Request:**

```json
{
    "student_id": 1
}
```

#### 3. **Remove Student from Class**

**Endpoint:** `DELETE /classes/{class_id}/students/{student_id}`

---

## 📱 **Mobile App UI Flow Recommendations**

### **Student App Screens:**

#### 1. **Login Screen**

-   Email/Password fields
-   "Login" button
-   Forgot password link
-   Clean, Islamic-themed design

#### 2. **Dashboard Screen**

-   Welcome message with student name
-   Progress stats (completion rate, pending assignments)
-   Quick access cards: "My Assignments", "Progress History"
-   Weekly assignments overview

#### 3. **Assignments List Screen**

-   List of assignments with:
    -   Title and type badge (Reading/Revision/Memorizing)
    -   Pages assigned with Quran page numbers
    -   Due date with overdue indicators
    -   Progress bar showing completion percentage
-   Filter options: Week, Status, Type

#### 4. **Assignment Detail Screen**

-   Assignment information (title, description, pages, due date)
-   Progress slider (0-100%)
-   Notes text area
-   "Mark Complete" button
-   Teacher feedback display (if available)

#### 5. **Progress History Screen**

-   Timeline view of completed assignments
-   Completion rates and teacher ratings
-   Achievement badges/milestones

### **Teacher App Screens:**

#### 1. **Dashboard Screen**

-   Overview stats (students, classes, assignments)
-   Recent student activity
-   Quick action buttons: "Create Assignment", "View Students"

#### 2. **Students Management Screen**

-   List of assigned students with progress indicators
-   Search and filter options
-   Individual student progress access

#### 3. **Assignment Creation Screen**

-   Form with assignment details
-   Page selector with Quran page numbers
-   Student/Class selector
-   Date pickers for assignment and due dates

#### 4. **Class Management Screen**

-   List of classes with student counts
-   Class progress overview
-   Add/remove students functionality

---

## 🛠️ **Development Notes**

### **Error Handling**

All endpoints return structured error responses:

```json
{
    "error": "Error message",
    "errors": {
        "field": ["Validation error message"]
    }
}
```

### **Pagination**

Large lists may be paginated. Look for meta information:

```json
{
  "data": [ ... ],
  "meta": {
    "current_page": 1,
    "total": 100,
    "per_page": 15
  }
}
```

### **Assignment Types**

-   **Reading** (`reading`): For reading practice (typically 10-20 pages)
-   **Revision** (`revision`): For reviewing previously memorized content (5-10 pages)
-   **Memorizing** (`memorizing`): For new memorization (1-2 pages)

### **Quran Pages**

-   Valid page numbers: 1-604 (complete Quran)
-   Pages are stored as JSON arrays: `[1, 2, 3]`

### **Week Numbers**

-   Week numbers range from 1-53 based on calendar weeks
-   Used for organizing assignments chronologically

### **Progress Tracking**

-   Progress percentage: 0-100 integer values
-   Completion status: boolean (true/false)
-   Teacher ratings: 1-5 stars (optional)

This API provides comprehensive functionality for a full-featured Quran school mobile application with separate student and teacher interfaces.
