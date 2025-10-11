import 'package:flutter/material.dart';

class SimpleAppBar extends StatefulWidget implements PreferredSizeWidget {
  final String textAppBar;
  final List<Widget>? actions;

  const SimpleAppBar({
    super.key,
    required this.textAppBar,
    this.actions,
  });

  @override
  Size get preferredSize => const Size.fromHeight(65);

  @override
  State<SimpleAppBar> createState() => _SimpleAppBarState();
}

class _SimpleAppBarState extends State<SimpleAppBar> {
  @override
  Widget build(BuildContext context) {
    return AppBar(
      surfaceTintColor: Theme.of(context).scaffoldBackgroundColor,
      backgroundColor: Theme.of(context).scaffoldBackgroundColor,
      elevation: 0,
      actions: (widget.actions ?? []),
      title: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Text(
            widget.textAppBar,
            style: TextStyle(fontSize: 24, color: Colors.white),
          ),
        ],
      ),
    );
  }
}
