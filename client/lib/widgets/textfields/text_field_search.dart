import 'package:flutter/material.dart';

class TextFieldSearch extends StatelessWidget {
  final TextEditingController textController;
  final String hintText;

  const TextFieldSearch({
    super.key, required this.hintText,
    required this.textController,
  });

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 30),
      child: TextFormField(
        controller: textController,
        decoration: InputDecoration(
            hintText: hintText,
            prefixIcon: Icon(Icons.search),
          )
        ,
      ),
    );
  }
}
