import 'package:flutter/material.dart';

class AppbarWithLogo extends StatelessWidget implements PreferredSizeWidget {
  final String text;
  const AppbarWithLogo({super.key, required this.text});

  @override
  Widget build(BuildContext context) {
    return AppBar(
      leading: Padding(
        padding: const EdgeInsets.fromLTRB(5, 0, 0, 0),
        child: Image.asset('assets/images/alhamidi_logo.png'),
      ),
      centerTitle: true,
      title: Text(text),
    );
  }

  @override
  // TODO: implement preferredSize
  Size get preferredSize => const Size.fromHeight(kToolbarHeight);
}
