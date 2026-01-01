import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:halqati/core/exceptions/api_exceptions.dart';
import 'package:halqati/core/utils/auth_utils.dart';
import 'package:halqati/provider/api_service_provider.dart';
import 'package:halqati/models/halaqa_class.dart';
import 'package:halqati/provider/profile_provider.dart';
import 'package:halqati/provider/token_notifier.dart';


final classesProvider = AsyncNotifierProvider<ClassesNotifier, List<HalaqaClass>?>(ClassesNotifier.new);


class ClassesNotifier extends AsyncNotifier<List<HalaqaClass>?> {
  @override
  Future<List<HalaqaClass>?> build() async {
    final tokenAsync = ref.watch(tokenProvider);

    // Wait for token to load
    if (tokenAsync.isLoading) {
      throw Exception('Token loading');
    }

    final token = tokenAsync.value;
    print("token class provider build: $token");
    if (token == null  || token.isEmpty) {
      // throw UnauthorizedException();
      print("No token found, returning empty state to stop loop.");
      return [];
    }

    try {
      print("try to get classes");
      return await ref.read(apiServiceProvider).getClasses(token??"");
    } catch (e) {
      print("error catched $e");
      if (e is UnauthorizedException) {
        // Clear token on auth error
        print("error type UnauthorizedException");
        await AuthHelper.handleLogout(ref);
        // ref.invalidate(authProvider);
      }
      print("rethrowing catch block in class provider");
      rethrow;
    }
  }

  Future<void> createClass (
    {
      required String name, // Replace with form controller values
      String description = "",
      int capacity = 20,
      String roomNumber = "",
      required List<String> scheduleDays,
      required String scheduleTime
    }
  ) async {
    final tokenAsync = ref.read(tokenProvider);

    // Wait for token to load
    if (tokenAsync.isLoading) {
      throw Exception('Token loading');
    }

    final token = tokenAsync.value;
    if (token == null) {
      throw UnauthorizedException();
    }

    try{
      await ref.read(apiServiceProvider)
        .createClass(
            token: token,
            name: name,
            description: description,
            capacity: capacity,
            roomNumber: roomNumber,
            scheduleDays: scheduleDays,
            scheduleTime: scheduleTime
        );
        ref.invalidateSelf();
    } catch(e){
      //e is ApiException
      if (e is UnauthorizedException) {
        print('Clearing token due to auth/API error');
        await AuthHelper.handleLogout(ref);
        // ref.invalidate(authProvider);
      }
      rethrow;
    }
  }
}

final teacherStatsProvider = Provider((ref) {
  final classesAsync = ref.watch(classesProvider);

  return classesAsync.maybeWhen(
    data: (classes) {
      if (classes == null) return {'classCount': 0, 'studentCount': 0};

      int classCount = classes.length;
      // Sum up students across all classes
      int studentCount = classes.fold(0, (sum, item) => sum + (item.studentCount));

      return {
        'classCount': classCount,
        'studentCount': studentCount,
      };
    },
    orElse: () => {'classCount': 0, 'studentCount': 0},
  );
});
