import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:halqati/widgets/appbar/appbar_with_button.dart';
import 'package:halqati/widgets/profile/student_profile_left.dart';


class StudentProfile extends StatelessWidget {
  const StudentProfile({
    super.key
  });

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppbarWithButton(
        text: "student_bottom_appbar.student_profile".tr(),
        addBackButton: true,
      ),
      body: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 30),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [
            // StudentProfileLeft()
          ],
        ),
      ),
    );
  }
}
