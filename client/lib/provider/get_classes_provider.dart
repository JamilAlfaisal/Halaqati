import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:halqati/provider/api_service_provider.dart';
import 'package:halqati/models/halaqa_class.dart';
import 'package:halqati/provider/token_provider.dart';

final getClassesProvider = FutureProvider<List<HalaqaClass>>((ref) async {
  final token = ref.read(tokenProvider);
  final api = ref.read(apiServiceProvider);
  final classes = await api.getClasses(token.value??"");
  return classes.isEmpty?[]:classes;
});