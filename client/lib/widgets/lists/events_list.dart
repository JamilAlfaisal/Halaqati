import 'package:flutter/material.dart';

class EventsList extends StatelessWidget {
  final String date;
  final String title;
  final String description;
  final void Function()? onTap;
  const EventsList({
    super.key,
    required this.title,
    required this.date,
    required this.description,
    this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  date,
                  style: Theme.of(context).textTheme.titleSmall,
                  overflow: TextOverflow.ellipsis,               // Better than clip
                  maxLines: 2,
                ),
                Text(
                  title,
                  style: Theme.of(context).textTheme.bodyLarge,
                  overflow: TextOverflow.ellipsis,               // Better than clip
                  maxLines: 2,
                ),
                Text(
                  description,
                  style: Theme.of(context).textTheme.titleSmall,
                  overflow: TextOverflow.ellipsis,               // Better than clip
                  maxLines: 2,
                ),
                // Divider(
                //   color: Theme.of(context).colorScheme.secondaryContainer,
                //   height: 20, // Specifies the total height of the divider area
                //   thickness: 2, // Specifies the thickness (height) of the line itself
                //   indent: 10, // Indentation (empty space) at the start of the divider
                //   endIndent: 10, // Indentation (empty space) at the end of the divider
                // ),
              ],
            ),
          ),
          Container(
            height: 100,
            width: 130,
            // clipBehavior: Clip.antiAlias,
            // decoration: BoxDecoration(
            //     borderRadius: BorderRadius.all(Radius.circular(16))
            // ),
            child: Image.asset(
              'assets/images/read.png',
              fit: BoxFit.fill,
            ),
          ),
        ],
      ),
    );
  }
}
