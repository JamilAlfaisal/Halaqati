import 'package:halqati/models/student.dart';

class HalaqaClass {
  final int? id;
  final String? name;
  final String? description;
  final int? capacity;
  final String? roomNumber;
  final List<String>?  days;
  final String? time;
  final List<Student>? students;

  HalaqaClass({
    required this.id,
    required this.name,
    this.description,
    this.capacity,
    this.days,
    this.roomNumber,
    this.students,
    this.time
  });

  factory HalaqaClass.fromJson(Map<String, dynamic> json) {
    // print(json);
    return HalaqaClass(
      id: json['id'] as int?,
      name: json['name'] as String?,
      description: json['description'] as String?,
      capacity: json['capacity'] as int?,
      roomNumber: json['room_number'] as String?,
      time: json['schedule']['time'] as String?,
      days: json['schedule']['days'] != null
          ? List<String>.from(json['schedule']['days'].map((x) => x.toString()))
          : null,
      students: json['students'] != null
          ? List<Student>.from(
          json['students'].map((x) => Student.fromJson(x as Map<String, dynamic>)))
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'description': description,
      'capacity': capacity,
      'room_number': roomNumber,
      'time': time,
      'days': days,
      'students': students?.map((x) => x.toJson()).toList(),
    };
  }

  HalaqaClass copyWith({
    int? id,
    String? name,
    String? description,
    int? capacity,
    String? roomNumber,
    List<String>? days,
    String? time,
    List<Student>? students,
  }) {
    return HalaqaClass(
      id: id ?? this.id,
      name: name ?? this.name,
      description: description ?? this.description,
      capacity: capacity ?? this.capacity,
      roomNumber: roomNumber ?? this.roomNumber,
      days: days ?? this.days,
      time: time ?? this.time,
      students: students ?? this.students,
    );
  }

  int get studentCount => students?.length ?? 0;

  bool get isFull => capacity != null && studentCount >= capacity!;

  String get daysString => days?.join(', ') ?? 'No days specified';

}