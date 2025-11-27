import 'package:flutter/material.dart';
import 'package:halqati/config/app_theme.dart';
import 'package:halqati/test/testing_widgets.dart';
import 'package:flutter_localizations/flutter_localizations.dart';
import 'package:easy_localization/easy_localization.dart';
import 'package:halqati/screens/common_screens/user_type_selection.dart';


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
        // '/': (context) => const TestingWidgets(),
        '/user_type_selection': (context) => const UserTypeSelection(),
      },
      home: TestingWidgets(),
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