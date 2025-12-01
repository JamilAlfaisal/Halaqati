import 'package:flutter/material.dart';
import 'package:easy_localization/easy_localization.dart';
import 'package:halqati/widgets/appbar/appbar_with_logo.dart';

class ProfileScreen extends StatelessWidget {
  const ProfileScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppbarWithLogo(text: "profile_screen.profile".tr()),
      body: Column(

      ),
    );
  }
}
