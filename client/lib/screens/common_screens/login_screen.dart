import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:halqati/widgets/appbar/appbar_with_logo.dart';
import 'package:easy_localization/easy_localization.dart';
import 'package:halqati/widgets/textfields/text_phone.dart';
import 'package:halqati/widgets/textfields/text_field_password.dart';
import 'package:halqati/widgets/buttons/full_width_button.dart';
import 'package:halqati/services/api_service.dart';
import 'package:halqati/storage/token_storage.dart';
import 'package:halqati/provider/token_provider.dart';

class LoginScreen extends ConsumerStatefulWidget {
  const LoginScreen({super.key});

  @override
  ConsumerState<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends ConsumerState<LoginScreen> {
  // Inside the _LoginScreenState class
  bool _isLoading = false; // State variable for loading indicator
  final TextEditingController _phoneController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  final _formKey = GlobalKey<FormState>();

  Future<void> _submitLogin(BuildContext context) async {
    Navigator.of(context).pushNamed('/home_app_bar');
    return;

    if (!_formKey.currentState!.validate()) {
      print("Form is NOT valid!");
      return;
    }

    setState(() {
      _isLoading = true;
    });

    final tokenService = TokenStorage();


    final String phone = _phoneController.text;
    final String pin = _passwordController.text;

    final apiService = ApiService();
    final token = await apiService.login(phone, pin);

    if (mounted) {
      setState(() {
        _isLoading = false;
      });
    }

    if (token != null) {
      await tokenService.saveToken(token);
      ref.invalidate(tokenProvider);
      // 🎉 Success!
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Login Successful!')),
      );
      // TODO: Save the token and navigate to the dashboard
      Navigator.of(context).pushNamed("/home_app_bar");
    } else {
      // 🛑 Failure
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Login failed. Please check your credentials or network.')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    final settings = ModalRoute.of(context)!.settings;
    final args = settings.arguments as Map<String, dynamic>;

    return Scaffold(
      appBar:  AppbarWithLogo(
        text: args['userType']=='teacher'?"user_type_selection.teacher".tr():"user_type_selection.student".tr(),
      ),
      body: SingleChildScrollView(
        child: Center(
          child: Padding(
            padding: const EdgeInsets.fromLTRB(20, 100, 20, 0),
            child: Form(
              key: _formKey,
              child: Column(
                spacing: 20,
                mainAxisAlignment: MainAxisAlignment.center,
                crossAxisAlignment: CrossAxisAlignment.center,
                children: [
                    Text(
                      "login_screen.welcome_back".tr(),
                      textAlign: TextAlign.center,
                      style: Theme.of(context).textTheme.bodyLarge,
                    ),
                  TextPhone(hintText: "login_screen.hint_phone".tr(),textController: _phoneController,title: "login_screen.phone".tr(),),
                  TextFieldPassword(hintText: "login_screen.hint_password".tr(),textController: _passwordController, title: "login_screen.password".tr(),),
                  FullWidthButton(
                    onPressed: _submitLogin,
                      text: _isLoading
                      ? "login_screen.logging_in".tr() // Show different text
                      :"login_screen.login_button".tr(),
                    isActive: !_isLoading,
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}
