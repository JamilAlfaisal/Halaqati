import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:halqati/core/exceptions/api_exceptions.dart';
import 'package:halqati/provider/api_service_provider.dart';
import 'package:halqati/models/halaqa_class.dart';
import 'package:halqati/provider/token_notifier.dart';

final createClassesProvider = FutureProvider.autoDispose<List<HalaqaClass>>((ref) async {
  final tokenAsync = ref.watch(tokenProvider);

  // Wait for token to load
  if (tokenAsync.isLoading) {
    throw Exception('Token loading');
  }

  final token = tokenAsync.value;
  if (token == null) {
    throw UnauthorizedException();
  }

  try {
    return await ref.read(apiServiceProvider).getClasses(token);
  } catch (e) {
    if (e is UnauthorizedException) {
      // Clear token on auth error
      await ref.read(tokenProvider.notifier).logout();
    }
    rethrow;
  }
});