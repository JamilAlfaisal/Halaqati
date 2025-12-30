import 'package:halqati/provider/token_notifier.dart';
import 'package:halqati/provider/api_service_provider.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:halqati/models/auth_user.dart';
import 'package:halqati/models/student.dart';
import 'package:halqati/models/teacher.dart';
import 'package:halqati/provider/teacher_provider.dart';
import 'package:halqati/provider/student_provider.dart';
import 'package:halqati/core/exceptions/api_exceptions.dart';
// lib/providers/auth_provider.dart

final authProvider = AsyncNotifierProvider<AuthNotifier, AuthUser?>(AuthNotifier.new);

class AuthNotifier extends AsyncNotifier<AuthUser?> {
  @override
  Future<AuthUser?> build() async {
    final tokenAsync = ref.watch(tokenProvider);
    if (tokenAsync.isLoading) return null;

    final token = tokenAsync.value;
    if (token == null) return null;

    try {
      final profileData = await ref.read(apiServiceProvider).getProfile(token);
      print('profile data downloaded successfully: $profileData');

      if (profileData == null) return null;

      if (profileData['role'] == 'teacher') {
        final teacher = Teacher.fromJson(profileData['user']);
        ref.read(teacherProvider.notifier).setTeacher(teacher);
        return AuthTeacher(teacher);
      } else {
        final student = Student.fromJson(profileData['user']);
        ref.read(studentProvider.notifier).setStudent(student);
        return AuthStudent(student);
      }
    } catch (e) {
      print('Error in AuthNotifier: $e');
      // ✅ For ANY error when fetching profile, clear the token
      // This prevents infinite retries with a bad token
      if (e is UnauthorizedException || e is ApiException) {
        print('Clearing token due to auth/API error');
        await ref.read(tokenProvider.notifier).logout();
      }
      rethrow;
    }
  }
}

// if (userData['role'] == 'teacher'){
// final Teacher teacher = Teacher.fromJson(userData['user']);
// ref.read(teacherProvider.notifier).setTeacher(teacher);
// Navigator.of(context).pushNamed("/home_app_bar");
// }else{
// final Student student = Student.fromJson(userData['user']);
// ref.read(studentProvider.notifier).setStudent(student);
// Navigator.of(context).pushNamed("/dashboard_app_bar");
// }