import 'package:flutter/material.dart';

class TopSnackBar {
  static void show(
    BuildContext context,
    String message, {
    Duration duration = const Duration(seconds: 3),
    Color? color,
  }) {
    final overlay = Overlay.of(context);

    late OverlayEntry entry;
    entry = OverlayEntry(
      builder: (context) => _TopSnackBarWidget(
        message: message,
        onDismissed: () => entry.remove(),
        duration: duration,
        color: color,
      ),
    );

    overlay.insert(entry);
  }
}

class _TopSnackBarWidget extends StatefulWidget {
  final String message;
  final VoidCallback onDismissed;
  final Duration duration;
  final Color? color;

  const _TopSnackBarWidget({
    required this.message,
    required this.onDismissed,
    required this.duration,
    this.color,
  });

  @override
  State<_TopSnackBarWidget> createState() => _TopSnackBarWidgetState();
}

class _TopSnackBarWidgetState extends State<_TopSnackBarWidget>
    with SingleTickerProviderStateMixin {
  late AnimationController _controller;
  late Animation<Offset> _offsetAnimation;
  late Color? color = widget.color;

  @override
  void initState() {
    super.initState();

    _controller = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 300),
    );
    _offsetAnimation = Tween<Offset>(
      begin: const Offset(0, -1),
      end: Offset.zero,
    ).animate(CurvedAnimation(parent: _controller, curve: Curves.easeOut));

    _controller.forward();

    Future.delayed(widget.duration, () {
      _controller.reverse().then((value) => widget.onDismissed());
    });
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Positioned(
      top: MediaQuery.of(context).padding.top + 10,
      left: 10,
      right: 10,
      child: SlideTransition(
        position: _offsetAnimation,
        child: Material(
          elevation: 6,
          borderRadius: BorderRadius.circular(8),
          color: color ?? Theme.of(context).colorScheme.primary,
          child: Padding(
            padding: const EdgeInsets.symmetric(vertical: 16, horizontal: 12),
            child: Text(
              widget.message,
              style: const TextStyle(color: Colors.white),
              textAlign: TextAlign.center,
            ),
          ),
        ),
      ),
    );
  }
}
