import 'package:flutter/material.dart';
import 'package:halqati/models/teacher.dart';
import 'package:easy_localization/easy_localization.dart';

class TeacherProfile extends StatelessWidget {
  final Teacher? teacher;
  const TeacherProfile({super.key, required this.teacher});

  @override
  Widget build(BuildContext context) {
    return Row(
      spacing: 10,
      children: [
        ClipRRect(
          borderRadius: BorderRadius.circular(50),
          child: Image.network(
            'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png',
            fit: BoxFit.cover,
            width: 100,
            height: 100,
          ),
        ),
        Column(
          children: [
            Text(
              teacher?.name??"teacher_profile.no_name".tr(),
              style: Theme.of(context).textTheme.bodyLarge,
            ),
            Text(
              teacher?.id.toString()??"teacher_profile.no_name".tr(),
              style: Theme.of(context).textTheme.bodyLarge,
            ),
          ],
        )
      ],
    );
  }
}
