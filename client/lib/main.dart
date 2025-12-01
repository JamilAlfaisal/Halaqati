import 'package:flutter/material.dart';
import 'package:halqati/config/app_theme.dart';
import 'package:halqati/screens/common_screens/login_screen.dart';
import 'package:halqati/test/testing_widgets.dart';
import 'package:flutter_localizations/flutter_localizations.dart';
import 'package:easy_localization/easy_localization.dart';
import 'package:halqati/screens/common_screens/user_type_selection.dart';
import 'package:halqati/screens/teacher/home/home_app_bar.dart';
import 'package:halqati/screens/teacher/home/add_halaqah_screen.dart';
void main() async{
  WidgetsFlutterBinding.ensureInitialized();
  await EasyLocalization.ensureInitialized();
  runApp(
    EasyLocalization(
      supportedLocales: const [Locale('en'), Locale('ar')],
      path: 'assets/translations',
      fallbackLocale: const Locale('ar'),
      child: const MyApp(),
    ),
  );
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  // This widget is the root of your application.
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      // locale: const Locale('ar'),
      locale: context.locale,
      supportedLocales: context.supportedLocales,
      localizationsDelegates: context.localizationDelegates,
      debugShowCheckedModeBanner: false,
      theme: AppTheme.lightTheme(),
      initialRoute: '/',
      routes: {
        '/': (context) => const TestingWidgets(),
        '/user_type_selection': (context) => const UserTypeSelection(),
        '/login_screen':(context) => const LoginScreen(),
        '/home_app_bar':(context) => const HomeAppBar(),
        '/add_halaqah_screen':(context) => const AddHalaqahScreen(),
      },
    );
  }
}

// ElevatedDark(onPressed: (){
// final currentLocale = context.locale;
// final newLocale = (currentLocale.languageCode == 'en')
// ? const Locale('ar') // Switch to Arabic
//     : const Locale('en');
// context.setLocale(newLocale);
// }, text: "change language",),