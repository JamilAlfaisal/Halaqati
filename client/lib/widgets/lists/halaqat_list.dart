import 'package:flutter/material.dart';

class HalaqatList extends StatelessWidget {
  final String title;
  final int studentNumber;
  final Function onTap;
  const HalaqatList({
    super.key,
    required this.title,
    required this.studentNumber,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: ()=>onTap(),
      child: Column(
        spacing: 5,
        children: [
          Text(
            title,
            style: TextTheme.of(context).bodyLarge,
          ),
          Text(
            "$studentNumber students",
            style: TextTheme.of(context).titleSmall,
          ),
        ],
      ),
    );
  }
}
