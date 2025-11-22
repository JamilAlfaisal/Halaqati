import 'package:flutter/material.dart';

class TextFieldPassword extends StatefulWidget {
  final TextEditingController textController;
  final String title;
  final String hintText;
  const TextFieldPassword({
    super.key, required this.hintText,
    required this.textController,
    required this.title
});

  @override
  State<TextFieldPassword> createState() => _TextFieldPasswordState();
}

class _TextFieldPasswordState extends State<TextFieldPassword> {
  bool display = false;
  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 30),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        mainAxisAlignment: MainAxisAlignment.center,
        spacing: 5,
        children: [
          Text(
            widget.title,
            style: TextTheme.of(context).bodyLarge,
          ),
          TextFormField(
            controller: widget.textController,
            obscureText: !display,
            decoration: InputDecoration(
              hintText: widget.hintText,
              suffixIcon: IconButton(
                  onPressed: (){
                    setState(() {
                      display = !display;
                    });
                  },
                  icon: Icon(display?Icons.visibility:Icons.visibility_off)
              )
            ),
          ),
        ],
      ),
    );
  }
}
