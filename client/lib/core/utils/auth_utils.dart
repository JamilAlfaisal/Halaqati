// lib/core/utils/auth_helper.dart
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:halqati/provider/token_notifier.dart';
import 'package:halqati/provider/profile_provider.dart';
import 'package:halqati/main.dart'; // For navigatorKey

class AuthHelper {
  /// Handles logout and clears all navigation stacks
  static Future<void> handleLogout(Ref ref) async {
    // 1. Clear token
    await ref.read(tokenProvider.notifier).logout();

    // 2. Clear all routes from navigation stack
    navigatorKey.currentState?.popUntil((route) => route.isFirst);

    // 3. Force authProvider to rebuild
    ref.invalidate(authProvider);
  }
}