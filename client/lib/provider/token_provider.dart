import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:halqati/storage/token_storage.dart';

final tokenStorageProvider = Provider<TokenStorage> ((ref){
  return TokenStorage();
});

final tokenProvider = FutureProvider<String?>((ref) async {
  final tokenStorage = ref.watch(tokenStorageProvider);
  return await tokenStorage.readToken();
});