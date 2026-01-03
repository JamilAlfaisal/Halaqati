import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:halqati/widgets/appbar/appbar_with_button.dart';


class AssignmentScreen extends StatelessWidget {
  const AssignmentScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppbarWithButton(
        text: "student_bottom_appbar.assignments".tr(),
        addBackButton: true,
      )
    );
  }
}
