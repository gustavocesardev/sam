import 'dart:typed_data';

import 'package:flutter/material.dart';

class PostImagesPage extends StatefulWidget {
  final String name;
  final String cursoInfo;
  final Uint8List? avatarBytes;
  final List<Uint8List> imagesBytes;
  final int comments;
  final int likes;

  const PostImagesPage({
    super.key,
    required this.name,
    required this.cursoInfo,
    this.avatarBytes,
    required this.imagesBytes,
    required this.comments,
    required this.likes,
  });

  @override
  State<PostImagesPage> createState() => _PostImagesPageState();
}

class _PostImagesPageState extends State<PostImagesPage> {
  int currentPage = 0;
  final PageController _pageController = PageController();

  @override
  void dispose() {
    _pageController.dispose();
    super.dispose();
  }

  Widget _buildUserHeader() {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
      child: Row(
        children: [
          CircleAvatar(
            radius: 24,
            backgroundColor: Colors.grey[800],
            backgroundImage: widget.avatarBytes != null
                ? MemoryImage(widget.avatarBytes!)
                : null,
            child: widget.avatarBytes == null ? const Icon(Icons.person) : null,
          ),
          const SizedBox(width: 12),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                widget.name,
                style: const TextStyle(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                ),
              ),
              Text(
                widget.cursoInfo,
                style: const TextStyle(fontSize: 14, color: Colors.grey),
              ),
            ],
          ),
          const Spacer(),
          IconButton(
            icon: const Icon(Icons.close),
            onPressed: () => Navigator.of(context).pop(),
          ),
        ],
      ),
    );
  }

  Widget _buildImageView(Uint8List bytes) {
    return InteractiveViewer(child: Image.memory(bytes, fit: BoxFit.contain));
  }

  Widget _buildBottomBar() {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
      child: Row(
        children: [
          const Spacer(),
          Text('${currentPage + 1}/${widget.imagesBytes.length}'),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.black,
      body: SafeArea(
        child: Column(
          children: [
            _buildUserHeader(),
            Expanded(
              child: PageView.builder(
                controller: _pageController,
                itemCount: widget.imagesBytes.length,
                onPageChanged: (index) {
                  setState(() {
                    currentPage = index;
                  });
                },
                itemBuilder: (context, index) {
                  return _buildImageView(widget.imagesBytes[index]);
                },
              ),
            ),
            _buildBottomBar(),
          ],
        ),
      ),
    );
  }
}
