import 'dart:ffi';

import 'package:flutter/material.dart';

class StudentList extends StatelessWidget {
  final String name;
  final int totalPagesMemorized;
  final String imgUrl;
  final Function onTap;
  const StudentList({
    super.key,
    required this.name,
    required this.totalPagesMemorized,
    required this.onTap,
    this.imgUrl = "assets/images/profile.png",
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: ()=> onTap(),
      child: Row (
        spacing: 5,
        children:[
          Container(
              decoration: BoxDecoration(
                borderRadius: BorderRadius.circular(15),
              ),
              height: 56,
              width:  56,
              child: Image.asset(imgUrl, fit: BoxFit.cover,)
          ),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                name,
                style: TextTheme.of(context).bodyLarge,
              ),
              Text(
                "Total pages memorized: $totalPagesMemorized",
                style: TextTheme.of(context).titleSmall,
              ),
            ],
          ),
        ]
      ),
    );
  }
}
