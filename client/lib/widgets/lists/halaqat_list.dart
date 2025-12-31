import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:halqati/provider/classes_provider.dart';

class HalaqatList extends StatelessWidget {
  final String title;
  final int studentNumber;
  final VoidCallback? onTap;
  const HalaqatList({
    super.key,
    required this.title,
    required this.studentNumber,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {

    return GestureDetector(
      onTap: onTap,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        spacing: 5,
        children: [
          Text(
            title,
            style: TextTheme.of(context).bodyLarge,
          ),
          Text(
            "$studentNumber ${'students'.tr()}",
            style: TextTheme.of(context).titleSmall,
          ),
        ],
      ),
    );
  }
}
