import 'package:flutter/material.dart';

class LoadingButtonSimple extends StatefulWidget {
  final Color color;
  final double width;
  final double height;

  const LoadingButtonSimple({
    super.key,
    this.color = Colors.blue,
    this.width = 150,
    this.height = 48,
  });

  @override
  State<LoadingButtonSimple> createState() => _LoadingButtonSimpleState();
}

class _LoadingButtonSimpleState extends State<LoadingButtonSimple> {
  @override
  Widget build(BuildContext context) {
    return ElevatedButton(
      onPressed: null,
      style: ElevatedButton.styleFrom(
        backgroundColor: widget.color,
        fixedSize: Size(widget.width, widget.height),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
      ),
      child: const SizedBox(
        height: 16,
        width: 16,
        child: CircularProgressIndicator(
          strokeWidth: 2,
          color: Colors.white,
        ),
      ),
    );
  }
}