

class Student{
  final int? id;
  final String? name;
  final String? email;
  final String? phone;
  final String? dateOfBirth;
  final int? classId;
  final List<bool>? memorizedPages;
  // final int? studentCount;

  Student({
    this.id,
    this.name,
    this.phone,
    this.email,
    this.dateOfBirth,
    this.classId,
    this.memorizedPages,
    // this.studentCount
  });

  factory Student.fromJson(Map<String, dynamic> json) {
    return Student(
      id: json['id'] as int?,
      name: json['name'] as String?,
      email: json['email'] as String?,
      phone: json['phone'] as String?,
      dateOfBirth: json['date_of_birth'] as String?,
      classId: json['class_id'] as int?,
      // studentCount: json['student_count'] as int?,
      memorizedPages: json['memorized_pages'] != null
          ? List<bool>.from(json['memorized_pages'].map((x) => x as bool))
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'email': email,
      'phone': phone,
      'date_of_birth': dateOfBirth,
      'class_id': classId,
      'memorized_pages': memorizedPages,
    };
  }

  Student copyWith({
    int? id,
    String? name,
    String? email,
    String? phone,
    String? dateOfBirth,
    int? classId,
    List<bool>? memorizedPages,
  }) {
    return Student(
      id: id ?? this.id,
      name: name ?? this.name,
      email: email ?? this.email,
      phone: phone ?? this.phone,
      dateOfBirth: dateOfBirth ?? this.dateOfBirth,
      classId: classId ?? this.classId,
      memorizedPages: memorizedPages ?? this.memorizedPages,
    );
  }
}