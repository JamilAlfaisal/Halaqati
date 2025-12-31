import 'package:flutter/material.dart';
import 'package:easy_localization/easy_localization.dart';
import 'package:halqati/widgets/appbar/appbar_with_logo.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:halqati/models/teacher.dart';
import 'package:halqati/provider/teacher_provider.dart';
import 'package:halqati/widgets/profile/teacher_profile.dart';

class ProfileScreen extends ConsumerStatefulWidget {
  const ProfileScreen({super.key});

  @override
  ConsumerState<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends ConsumerState<ProfileScreen> {
  late Teacher ?teacher = ref.watch(teacherProvider);
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppbarWithLogo(text: "profile_screen.profile".tr()),
      body: SingleChildScrollView(
        child: Padding(
          padding: const EdgeInsets.symmetric(horizontal: 30,vertical: 10),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              TeacherProfile(teacher: teacher),
              SizedBox(height: 10,),
              Text(
                "teacher_profile.email_details".tr(),
                style: Theme.of(context).textTheme.bodyLarge,
              ),
            ],
          ),
        ),
      ),
    );
  }
}
